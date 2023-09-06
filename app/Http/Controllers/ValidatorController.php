<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ValidatorController extends Controller
{
    public function connect(){
        if (!pg_connect("host=localhost port=5432 dbname=iclop user=postgres password=auzanzulhazmi")) {
            throw new \Exception('SYSTEM_ERROR: Cant connect to database!');
        }else{
            return pg_connect("host=localhost port=5432 dbname=iclop user=postgres password=auzanzulhazmi");
        }

    }

    public function execute_test($connection, $code){
        $query = "CREATE OR REPLACE FUNCTION public.testschema() \r\n";
        $query .= "RETURNS SETOF TEXT LANGUAGE plpgsql AS $$ \r\n";
        $query .= "BEGIN \r\n";
        $query .= "RETURN NEXT \r\n";
        $query .= "results_eq( \r\n";
        $query .=  "'" . $code . "', \r\n";
        $query .= "'SELECT * FROM customers', \r\n";
        $query .= "'Tabel Customers Tersedia' \r\n";
        $query .= "); \r\n";
        $query .= "END; \r\n";
        $query .= "$$; \r\n";
        $query .= "SELECT * FROM runtests('public'::name) offset 1 limit 1;";

        $result = pg_query($connection, $query);
        if (!$result) {
            throw new \Exception($this->displayError(pg_last_error($connection)));
        } else {
            return $result;
        }
    }

    public function disconnect_from_database($connection)
    {
        pg_close($connection);
    }

    public function get_test_result($result){
        while ($row = pg_fetch_assoc($result)) {
            $test_result = $row['runtests'];
        }

        return $test_result;
    }

    public function execute_code(Request $request){
        $connection = $this->connect();
        $stat = pg_connection_status($connection);

        if ($stat === PGSQL_CONNECTION_OK) {
            $stat =  'Connection status ok';
        } else {
            $stat = 'Connection status bad';
        }

        $result = $this->execute_test($connection, $request->code);
        $test_result = $this->get_test_result($result);
        $this->disconnect_from_database($connection);

        return response()->json(['result' => $test_result]);
    }
}

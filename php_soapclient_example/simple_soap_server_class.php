<?php

/**
 * Methods of our simple SOAP service.
 */
class SimpleSoapServer {

    const FILENAME = '../../../runtime/data.txt';

    /**
     * Inserts data. Invoked remotely from SOAP client.
     *
     * @param $data Data to insert.
     * @return Resume of operation.
     */
    public function insertData($data) {
//        $writtenBytes = file_put_contents(self::FILENAME, $data . '<br>', FILE_APPEND);
        $writtenBytes = file_put_contents(self::FILENAME, $data . '<br>', FILE_APPEND);
        if ($writtenBytes) {
            $response = "$writtenBytes bytes have been inserted.";
        } else {
            $response = 'Error inserted data.';
        }

        return $response;
    }

    /**
     * Reads data. Invoked remotely from SOAP client.
     *
     * @return Data of file.
     */
    public function readData() {
        $contents = file_get_contents(self::FILENAME);

        return $contents;
    }
}

<?php

function sendWaMessage($no_hp, $pesan, $terminal = null, $timeout = 5)
{
    try {
        if ($timeout == null) {
            $timeout = 5;
        }

        $no_hp = preg_replace("/[^0-9]/", "", $no_hp);
        $no_hp = str_replace('/', '', $no_hp);
        $no_hp = str_replace('-', '', $no_hp);
        $no_hp = str_replace(' ', '', $no_hp);
        $no_hp = str_replace('+', '', $no_hp);
        $no_hp = str_replace('#', '', $no_hp);
        $no_hp = str_replace('(', '', $no_hp);
        $no_hp = str_replace(')', '', $no_hp);
        $no_hp = str_replace('"', '', $no_hp);
        $no_hp = str_replace("'", "", $no_hp);

        if (substr($no_hp, 0, 1) === "0") {
            $no_hp = "62" . substr($no_hp, 1);
        }

        if ($terminal == null || config('app.env', 'local') !== 'production') {
            $terminal = "F";
        }

        if ($no_hp !== "") {
            $kode_token = env('WHATSVA_TOKEN', "sJJjkyYx");
            $id_device = env('WHATSVA_ID_DEVICE', "62");
            $url_endpoint = env('WHATSVA_URL_SENDMSG', '');

            $to = $no_hp . "@s.whatsapp.net";

            if ($terminal != "T") {
                // $pesan = urlencode($pesan);
                $pesan .= "\n\n*Note: Pesan ini dikirim otomatis dari Aplikasi IGP GROUP. Mohon untuk menyimpan nomor ini agar tidak dianggap sebagai _SPAM_. Terima kasih.*";

                $pesan = str_replace("<strong> ", "*", $pesan);
                $pesan = str_replace("<strong>", "*", $pesan);
                $pesan = str_replace(" </strong>", "*", $pesan);
                $pesan = str_replace("</strong>", "*", $pesan);
                $pesan = str_replace("<a href='" . url('login') . "'>", "", $pesan);
                $pesan = str_replace("<a href='" . url('password/reset') . "'>", "", $pesan);
                $pesan = str_replace("</a>", "", $pesan);

                $payload = json_encode(["id_device" => $id_device, "tujuan" => $to, "number" => $to, "message" => $pesan]);

                $curl = curl_init();
                curl_setopt_array(
                    $curl,
                    array(
                        CURLOPT_URL => $url_endpoint,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => $timeout,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $payload,
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type:application/json",
                            "apikey: " . $kode_token,
                        ),
                    )
                );

                $response = curl_exec($curl);

                curl_close($curl);

                // {"success":false,"message":"device not found"}
                // {"success":true,"message":"success to sending","data":{"key":{"remoteJid":"6285695547193@s.whatsapp.net","fromMe":true,"id":"3EB049C7E416"},"message":{"extendedTextMessage":{"text":"Test Kirim WA"}},"messageTimestamp":"1614845238","status":"SERVER_ACK"}}

                if ($response != null) {
                    $response = json_decode($response, true);
                    $response_success = $response["success"];
                    $response_message = $response["message"];

                    if (!$response_success) {
                        // echo "cURL Error #:" . $err;
                        try {
                            // kirim telegram
                            $token_bot_internal = config('app.token_igp_astra_bot', '-');
                            $telegram_id_ian = config('app.telegram_id_ian', '-');

                            $pesan = "sendWaMessage: cURL Error #: " . $no_hp . " - " . $response_message;

                            $data_telegram = array(
                                'chat_id' => $telegram_id_ian,
                                'text' => $pesan,
                                'parse_mode' => 'HTML'
                            );
                            $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                        } catch (Exception $exception) {
                        }
                        return false;
                    } else {
                        // echo $response;
                        return true;
                    }
                } else {
                    // echo "cURL Error #:" . $err;
                    try {
                        // kirim telegram
                        $token_bot_internal = config('app.token_igp_astra_bot', '-');
                        $telegram_id_ian = config('app.telegram_id_ian', '-');

                        $pesan = "sendWaMessage: cURL Error #: " . $no_hp . " - response NULL";

                        $data_telegram = array(
                            'chat_id' => $telegram_id_ian,
                            'text' => $pesan,
                            'parse_mode' => 'HTML'
                        );
                        $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                    } catch (Exception $exception) {
                    }
                    return false;
                }
            } else {
                $pesan .= "<br><br>*Note: Pesan ini dikirim otomatis dari Aplikasi IGP GROUP. Mohon untuk menyimpan nomor ini agar tidak dianggap sebagai _SPAM_. Terima kasih.*";

                $pesan = str_replace("<strong> ", "*", $pesan);
                $pesan = str_replace("<strong>", "*", $pesan);
                $pesan = str_replace(" </strong>", "*", $pesan);
                $pesan = str_replace("</strong>", "*", $pesan);
                $pesan = str_replace("<a href='" . url('login') . "'>", "", $pesan);
                $pesan = str_replace("<a href='" . url('password/reset') . "'>", "", $pesan);
                $pesan = str_replace("</a>", "", $pesan);

                $param1 = '{"id_device": "' . $id_device . '", "message": "' . $pesan . '", "tujuan": "' . $to . '", "number": "' . $to . '"}';
                $param1 = "'$param1'";
                $script = 'curl --location --request POST -H "Content-Type:application/json" "' . $url_endpoint . '" --header "apikey: ' . $kode_token . '" --data ' . $param1 . ' --connect-timeout ' . $timeout . ' --max-time ' . $timeout . ' --insecure';

                $script = str_replace('<br>', '\n', $script);
                return $script;
            }
        } else {
            if ($terminal != "T") {
                return false;
            } else {
                return "";
            }
        }
    } catch (Exception $ex) {
        //echo "Send Message Error: ".$ex;
        try {
            // kirim telegram
            $token_bot_internal = config('app.token_igp_astra_bot', '-');
            $telegram_id_ian = config('app.telegram_id_ian', '-');

            $pesan = "sendWaMessage: Send Message Error: " . $ex;

            $data_telegram = array(
                'chat_id' => $telegram_id_ian,
                'text' => $pesan,
                'parse_mode' => 'HTML'
            );
            $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
        } catch (Exception $exception) {
        }
        return false;
    }
}

function sendWaGroup($id_group, $pesan, $terminal = null, $timeout = 5)
{
    try {
        if ($timeout == null) {
            $timeout = 5;
        }

        $id_group = preg_replace("/[^0-9-]/", "", $id_group);
        $id_group = str_replace('/', '', $id_group);
        $id_group = str_replace(' ', '', $id_group);
        $id_group = str_replace('+', '', $id_group);
        $id_group = str_replace('#', '', $id_group);
        $id_group = str_replace('(', '', $id_group);
        $id_group = str_replace(')', '', $id_group);
        $id_group = str_replace('"', '', $id_group);
        $id_group = str_replace("'", "", $id_group);

        if (substr($id_group, 0, 1) === "0") {
            $id_group = "62" . substr($id_group, 1);
        }

        if ($terminal == null || config('app.env', 'local') !== 'production') {
            $terminal = "F";
        }

        if ($id_group !== "") {
            $kode_token = env('WHATSVA_TOKEN', "sJJjkyYx");
            $id_device = env('WHATSVA_ID_DEVICE', "62");
            $url_endpoint = env('WHATSVA_URL_SENDGROUP', '');

            $to = $id_group . "@g.us";

            if ($terminal != "T") {
                $pesan .= "\n\n*Note: Pesan ini dikirim otomatis dari Aplikasi IGP GROUP. Mohon untuk menyimpan nomor ini agar tidak dianggap sebagai _SPAM_. Terima kasih.*";

                $pesan = str_replace("<strong> ", "*", $pesan);
                $pesan = str_replace("<strong>", "*", $pesan);
                $pesan = str_replace(" </strong>", "*", $pesan);
                $pesan = str_replace("</strong>", "*", $pesan);
                $pesan = str_replace("<a href='" . url('login') . "'>", "", $pesan);
                $pesan = str_replace("<a href='" . url('password/reset') . "'>", "", $pesan);
                $pesan = str_replace("</a>", "", $pesan);

                $payload = json_encode(["id_device" => $id_device, "tujuan" => $to, "number" => $to, "message" => $pesan]);

                $curl = curl_init();
                curl_setopt_array(
                    $curl,
                    array(
                        CURLOPT_URL => $url_endpoint,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => $timeout,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $payload,
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type:application/json",
                            "apikey: " . $kode_token,
                        ),
                    )
                );

                $response = curl_exec($curl);

                curl_close($curl);

                // {"success":false,"message":"device not found"}
                // {"success":true,"message":"success to sending","data":{"key":{"remoteJid":"6285695547193@s.whatsapp.net","fromMe":true,"id":"3EB049C7E416"},"message":{"extendedTextMessage":{"text":"Test Kirim WA"}},"messageTimestamp":"1614845238","status":"SERVER_ACK"}}

                if ($response != null) {
                    $response = json_decode($response, true);
                    $response_success = $response["success"];
                    $response_message = $response["message"];

                    if (!$response_success) {
                        // echo "cURL Error #:" . $err;
                        try {
                            // kirim telegram
                            $token_bot_internal = config('app.token_igp_astra_bot', '-');
                            $telegram_id_ian = config('app.telegram_id_ian', '-');

                            $pesan = "sendWaGroup: cURL Error #: " . $to . " - " . $response_message;

                            $data_telegram = array(
                                'chat_id' => $telegram_id_ian,
                                'text' => $pesan,
                                'parse_mode' => 'HTML'
                            );
                            $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                        } catch (Exception $exception) {
                        }
                        return false;
                    } else {
                        // echo $response;
                        return true;
                    }
                } else {
                    // echo "cURL Error #:" . $err;
                    try {
                        // kirim telegram
                        $token_bot_internal = config('app.token_igp_astra_bot', '-');
                        $telegram_id_ian = config('app.telegram_id_ian', '-');

                        $pesan = "sendWaGroup: cURL Error #: " . $to . " - response NULL";

                        $data_telegram = array(
                            'chat_id' => $telegram_id_ian,
                            'text' => $pesan,
                            'parse_mode' => 'HTML'
                        );
                        $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                    } catch (Exception $exception) {
                    }
                    return false;
                }
            } else {
                $pesan .= "<br><br>*Note: Pesan ini dikirim otomatis dari Aplikasi IGP GROUP. Mohon untuk menyimpan nomor ini agar tidak dianggap sebagai _SPAM_. Terima kasih.*";

                $pesan = str_replace("<strong> ", "*", $pesan);
                $pesan = str_replace("<strong>", "*", $pesan);
                $pesan = str_replace(" </strong>", "*", $pesan);
                $pesan = str_replace("</strong>", "*", $pesan);
                $pesan = str_replace("<a href='" . url('login') . "'>", "", $pesan);
                $pesan = str_replace("<a href='" . url('password/reset') . "'>", "", $pesan);
                $pesan = str_replace("</a>", "", $pesan);

                $param1 = '{"id_device": "' . $id_device . '", "message": "' . $pesan . '", "tujuan": "' . $to . '"}';
                $param1 = "'$param1'";
                $script = 'curl --location --request POST -H "Content-Type:application/json" "' . $url_endpoint . '" --header "apikey: ' . $kode_token . '" --data ' . $param1 . ' --connect-timeout ' . $timeout . ' --max-time ' . $timeout . ' --insecure';

                $script = str_replace('<br>', '\n', $script);
                return $script;
            }
        } else {
            if ($terminal != "T") {
                return false;
            } else {
                return "";
            }
        }
    } catch (Exception $ex) {
        //echo "Send Message Error: ".$ex;
        try {
            // kirim telegram
            $token_bot_internal = config('app.token_igp_astra_bot', '-');
            $telegram_id_ian = config('app.telegram_id_ian', '-');

            $pesan = "sendWaGroup: Send Message Error: " . $ex;

            $data_telegram = array(
                'chat_id' => $telegram_id_ian,
                'text' => $pesan,
                'parse_mode' => 'HTML'
            );
            $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
        } catch (Exception $exception) {
        }
        return false;
    }
}

function sendWaFile($no_hp, $pesan, $file, $timeout = 5)
{
    try {
        if ($timeout == null) {
            $timeout = 5;
        }

        $no_hp = preg_replace("/[^0-9]/", "", $no_hp);
        $no_hp = str_replace('/', '', $no_hp);
        $no_hp = str_replace('-', '', $no_hp);
        $no_hp = str_replace(' ', '', $no_hp);
        $no_hp = str_replace('+', '', $no_hp);
        $no_hp = str_replace('#', '', $no_hp);
        $no_hp = str_replace('(', '', $no_hp);
        $no_hp = str_replace(')', '', $no_hp);
        $no_hp = str_replace('"', '', $no_hp);
        $no_hp = str_replace("'", "", $no_hp);

        if (substr($no_hp, 0, 1) === "0") {
            $no_hp = "62" . substr($no_hp, 1);
        }

        if ($no_hp !== "") {
            $pesan = str_replace("<strong> ", "*", $pesan);
            $pesan = str_replace("<strong>", "*", $pesan);
            $pesan = str_replace(" </strong>", "*", $pesan);
            $pesan = str_replace("</strong>", "*", $pesan);
            $pesan = str_replace("<a href='" . url('login') . "'>", "", $pesan);
            $pesan = str_replace("<a href='" . url('password/reset') . "'>", "", $pesan);
            $pesan = str_replace("</a>", "", $pesan);
            $pesan = urlencode($pesan);

            $kode_token = env('WHATSVA_TOKEN', "sJJjkyYx");
            $id_device = env('WHATSVA_ID_DEVICE', "62");
            $url_endpoint = env('WHATSVA_URL_SENDFILE', '');

            $to = $no_hp . "@s.whatsapp.net";

            $alamat_gateway = $url_endpoint;
            $url_opt = $alamat_gateway . "?id_device=" . $id_device . "&tujuan=" . $to . "&number=" . $to . "&message=" . $pesan;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url_opt,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => array('file' => new \CURLFILE($file)),
                CURLOPT_HTTPHEADER => array(
                    "apikey: " . $kode_token
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            // {"success":false,"message":"device not found"}
            // {"success":true,"message":"success to sending","data":{"key":{"remoteJid":"6285695547193@s.whatsapp.net","fromMe":true,"id":"3EB049C7E416"},"message":{"extendedTextMessage":{"text":"Test Kirim WA"}},"messageTimestamp":"1614845238","status":"SERVER_ACK"}}

            if ($response != null) {
                $response = json_decode($response, true);
                $response_success = $response["success"];
                $response_message = $response["message"];

                if (!$response_success) {
                    // echo "cURL Error #:" . $err;
                    try {
                        // kirim telegram
                        $token_bot_internal = config('app.token_igp_astra_bot', '-');
                        $telegram_id_ian = config('app.telegram_id_ian', '-');

                        $pesan = "sendWaFile: cURL Error #: " . $no_hp . " - " . $response_message;

                        $data_telegram = array(
                            'chat_id' => $telegram_id_ian,
                            'text' => $pesan,
                            'parse_mode' => 'HTML'
                        );
                        $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                    } catch (Exception $exception) {
                    }
                    return false;
                } else {
                    // echo $response;
                    return true;
                }
            } else {
                // echo "cURL Error #:" . $err;
                try {
                    // kirim telegram
                    $token_bot_internal = config('app.token_igp_astra_bot', '-');
                    $telegram_id_ian = config('app.telegram_id_ian', '-');

                    $pesan = "sendWaFile: cURL Error #: " . $no_hp . " - response NULL";

                    $data_telegram = array(
                        'chat_id' => $telegram_id_ian,
                        'text' => $pesan,
                        'parse_mode' => 'HTML'
                    );
                    $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                } catch (Exception $exception) {
                }
                return false;
            }
        } else {
            return false;
        }
    } catch (Exception $ex) {
        //echo "Send Message Error: ".$ex;
        try {
            // kirim telegram
            $token_bot_internal = config('app.token_igp_astra_bot', '-');
            $telegram_id_ian = config('app.telegram_id_ian', '-');

            $pesan = "sendWaFile: Send Message Error: " . $ex;

            $data_telegram = array(
                'chat_id' => $telegram_id_ian,
                'text' => $pesan,
                'parse_mode' => 'HTML'
            );
            $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
        } catch (Exception $exception) {
        }
        return false;
    }
}

function cekStatusWaDevice($notif = "F", $kode_token = null, $id_device = null, $timeout = 5)
{
    try {
        if ($kode_token == null) {
            $kode_token = env('WHATSVA_TOKEN', "sJJjkyYx");
        }
        if ($id_device == null) {
            $id_device = env('WHATSVA_ID_DEVICE', "62");
        }

        $url_endpoint = env('WHATSVA_URL_CEKDEVICE', '');

        if ($timeout == null) {
            $timeout = 5;
        }

        $token_bot_internal = config('app.token_igp_astra_bot', '-');
        $telegram_id = config('app.telegram_id_group_it', '-');

        $payload = json_encode(["id_device" => $id_device]);

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url_endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type:application/json",
                    "apikey: " . $kode_token,
                ),
            )
        );

        $response = curl_exec($curl);

        curl_close($curl);

        if ($response != null) {
            $response = json_decode($response, true);
            $response_success = $response["success"];
            $response_message = $response["message"];

            if (!$response_success) {
                try {
                    $pesan = "cekStatusWaDevice: cURL Error #: " . $id_device . " - " . $response_message;

                    $data_telegram = array(
                        'chat_id' => $telegram_id,
                        'text' => $pesan,
                        'parse_mode' => 'HTML'
                    );
                    $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                } catch (Exception $exception) {
                }
                return false;
            } else {
                $response_data = $response["data"];
                if ($response_data != null) {
                    $response_data_id = $response_data["id"];
                    $response_data_nama_device = $response_data["nama_device"];
                    $response_data_status = $response_data["status"];
                    $response_data_id_user = $response_data["id_user"];

                    // echo "ID Device: ".$response_data_id;
                    // echo "<br>";
                    // echo "Nama Device: ".$response_data_nama_device;
                    // echo "<br>";
                    // echo "Status: ".$response_data_status;
                    // echo "<br>";
                    // echo "ID User: ".$response_data_id_user;

                    if (strtolower($response_data_status) != strtolower("paired")) {
                        try {
                            $pesan = "<strong>STATUS DEVICE WA API</strong>";
                            $pesan .= "\n\nID Device: " . $response_data_id;
                            $pesan .= "\nNama Device: " . $response_data_nama_device;
                            $pesan .= "\nStatus: " . $response_data_status;
                            $pesan .= "\nID User: " . $response_data_id_user;
                            $pesan .= "\n\n<strong>Note: Pesan ini dikirim otomatis dari Aplikasi IGP GROUP. Mohon untuk menyimpan nomor ini agar tidak dianggap sebagai <i>SPAM</i>. Terima kasih.</strong>";

                            $data_telegram = array(
                                'chat_id' => $telegram_id,
                                'text' => $pesan,
                                'parse_mode' => 'HTML'
                            );
                            $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                        } catch (Exception $exception) {
                        }
                        return false;
                    } else {
                        if ($notif === "T") {
                            try {
                                $pesan = "<strong>STATUS DEVICE WA API</strong>";
                                $pesan .= "\n\nID Device: " . $response_data_id;
                                $pesan .= "\nNama Device: " . $response_data_nama_device;
                                $pesan .= "\nStatus: " . $response_data_status;
                                $pesan .= "\nID User: " . $response_data_id_user;
                                $pesan .= "\n\n<strong>Note: Pesan ini dikirim otomatis dari Aplikasi IGP GROUP. Mohon untuk menyimpan nomor ini agar tidak dianggap sebagai <i>SPAM</i>. Terima kasih.</strong>";

                                $data_telegram = array(
                                    'chat_id' => $telegram_id,
                                    'text' => $pesan,
                                    'parse_mode' => 'HTML'
                                );
                                $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                            } catch (Exception $exception) {
                            }
                        }
                        return true;
                    }
                } else {
                    try {
                        $pesan = "cekStatusWaDevice: cURL Error #: " . $id_device . " - response data NULL";

                        $data_telegram = array(
                            'chat_id' => $telegram_id,
                            'text' => $pesan,
                            'parse_mode' => 'HTML'
                        );
                        $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
                    } catch (Exception $exception) {
                    }
                    return false;
                }
            }
        } else {
            try {
                $pesan = "cekStatusWaDevice: cURL Error #: " . $id_device . " - response NULL";

                $data_telegram = array(
                    'chat_id' => $telegram_id,
                    'text' => $pesan,
                    'parse_mode' => 'HTML'
                );
                $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
            } catch (Exception $exception) {
            }
            return false;
        }
    } catch (Exception $ex) {
        try {
            $pesan = "cekStatusWaDevice: cURL Error #: " . $id_device . " - " . $ex;

            $data_telegram = array(
                'chat_id' => $telegram_id,
                'text' => $pesan,
                'parse_mode' => 'HTML'
            );
            $result = KirimPerintah("sendMessage", $token_bot_internal, $data_telegram);
        } catch (Exception $exception) {
        }
        return false;
    }
}

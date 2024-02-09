<?php

class Time_class
{
    protected $brt_time_zone;
    protected $utc_time_zone;

    public function __construct()
    {
        // Define os fusos horários
        $this->brt_time_zone = new DateTimeZone('America/Sao_Paulo');
        $this->utc_time_zone = new DateTimeZone('UTC');
    }

    public function convert_brt_utc($brt_date_time_string)
    {
        $brt_date_time = new DateTime($brt_date_time_string, $this->brt_time_zone);
        $brt_date_time->setTimezone($this->utc_time_zone);

        return $brt_date_time->format('Y-m-d H:i:s');
    }

    public function convert_utc_brt($utc_date_time_string)
    {
        $utc_date_time = new DateTime($utc_date_time_string, $this->utc_time_zone);
        $utc_date_time->setTimezone($this->brt_time_zone);

        return $utc_date_time->format('Y-m-d H:i:s');
    }

    public function get_time_utc()
    {
        $current_date_time = new DateTime('now', $this->utc_time_zone);
        return $current_date_time->format('Y-m-d\TH:i:sP');
    }

    public function get_timezone_ip($client_ip)
    {
        // Faz uma requisição ao serviço ipinfo.io para obter informações de geolocalização
        $ipinfo_url = "https://ipinfo.io/{$client_ip}/json"; ///limite de 1k de request por dia, tentar atribuir localizacao por cliente no db 
        $ipinfo_response = file_get_contents($ipinfo_url);

        if ($ipinfo_response !== false) {
            $ipinfo_data = json_decode($ipinfo_response, true);

            if (isset($ipinfo_data['timezone'])) {
                return new DateTimeZone($ipinfo_data['timezone']);
            }
        }

        // Se não for possível obter o fuso horário, retorna o fuso horário padrão UTC
        return $this->utc_time_zone;
    }
}


// // Criando uma instância da classe Time
// $time = new Time();

// // Exemplo de conversão de horário local (BRT) para UTC
// $brt_datetime = '2024-02-09 12:30:00';
// $utc_datetime = $time->convert_brt_utc($brt_datetime);
// echo "Horário BRT ($brt_datetime) convertido para UTC: $utc_datetime\n\n";

// // Exemplo de conversão de horário UTC para local (BRT)
// $utc_datetime = '2024-02-09 15:30:00';
// $brt_datetime = $time->convert_utc_brt($utc_datetime);
// echo "Horário UTC ($utc_datetime) convertido para BRT: $brt_datetime\n\n";

// // Exemplo de obtenção da data e hora atual em UTC
// $current_utc_time = $time->get_time_utc();
// echo "Data e Hora Atuais em UTC: $current_utc_time\n\n";

// // Exemplo de obtenção do fuso horário pelo IP (substitua 'CLIENT_IP' pelo IP real do cliente)
// $client_ip = 'CLIENT_IP';
// $client_timezone = $time->get_timezone_ip($client_ip);
// echo "Fuso Horário do Cliente: $client_timezone\n";
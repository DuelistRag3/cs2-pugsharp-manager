<?php

namespace App\Http\Controllers;

use App\Models\Server;
use xPaw\SourceQuery\SourceQuery;
use Barryvdh\Debugbar\Facades\Debugbar;

class RconController extends Controller
{
    public function getServerInfo($serverID)
    {
        $server = Server::find($serverID);
        if ($server) {
            $query = new SourceQuery();
            try {
                $query->Connect($server->ip_address, $server->port, 1, SourceQuery::SOURCE);
                $info = $query->GetInfo();
                $query->Disconnect();

                Debugbar::info('Server status fetched successfully');

                return [
                    'status' => 'online',
                    'name' => $info['HostName'],
                    'players' => $info['Players'],
                    'max_players' => $info['MaxPlayers'],
                ];
            } catch (\Exception $e) {
                Debugbar::error('Server status error: ' . $e->getMessage());
                return ['status' => 'offline'];
            }
        } else {
            Debugbar::warning('Server not found with ID: ' . $serverID);
            return ['status' => 'unknown'];
        }
    }
    
    /**
     * Example method to send a command to the RCON server.
     *
     * @param  string  $command
     * @return \Illuminate\Http\Response
     */
    public function sendCommand($serverID, $command)
    {
        $server = Server::find($serverID);
        if (!$server) {
            return ['error' => 'Server not found'];
        }

        $query = new SourceQuery();
        try {
            $query->Connect($server->ip_address, $server->port, 1, SourceQuery::SOURCE);
            $query->SetRconPassword($server->rcon_password);
            $result = $query->Rcon($command);
            $query->Disconnect();
            return $result;
        } catch (\Exception $e) {
            return ['error' => "Failed to send command: " . $e->getMessage()];
        }
    }

}

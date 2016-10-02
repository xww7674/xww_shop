<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Log;

class DeploymentController extends Controller
{
    public function deploy(Request $request)
    {
        $commands = ['cd /var/www/laravel-ubuntu', 'sudo -Hu www-data git pull'];
        $signature = $request->header('X-Hub-Signature');
        Log::info($signature);
        $payload = file_get_contents('php://input');
        Log::info($payload);
        if ($this->isFromGithub($payload, $signature)) {
            foreach ($commands as $command) {
                Log::info($command);
                shell_exec($command);
            }
            http_response_code(200);
        } else {
            abort(403);
        }
    }
    private function isFromGithub($payload, $signature)
    {
        $hash  = 'sha1=' . hash_hmac('sha1', $payload, env('GITHUB_DEPLOY_TOKEN'), false) === $signature;
        Log::info($hash);
        return $hash;
    }
}

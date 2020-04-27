<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use phpseclib\Crypt\RSA;

class KeysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anvil:keys
                                  {--force : Overwrite keys they already exist}
                                  {--length=2048 : The length of the private key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the ssh keys for git authentication';

    /**
     * Execute the console command.
     *
     * @param RSA $rsa
     * @return mixed
     */
    public function handle(RSA $rsa)
    {
        [$publicKey, $privateKey] = [
            storage_path('.ssh/id_rsa.pub'),
            storage_path('.ssh/id_rsa'),
        ];

        if ((file_exists($publicKey) || file_exists($privateKey)) && ! $this->option('force')) {
            $this->warn('ssh keys already exist. Use the --force option to overwrite them.');
        } else {
            $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
            $rsa->setComment('anvil@'.gethostname());
            $keys = $rsa->createKey($this->input ? (int) $this->option('length') : 2048);

            file_put_contents($publicKey, Arr::get($keys, 'publickey'));
            file_put_contents($privateKey, Arr::get($keys, 'privatekey'));

            chmod($privateKey, 0600);
            chmod($publicKey, 0644);

            $this->info('Encryption keys generated successfully.');
        }
    }
}

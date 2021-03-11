<?php
namespace CustomApi\Controller;

use \LimeExtra\Controller;

class RestApiFiles extends Controller {

    // Set correct headers
    private function setHeaders() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users.csv"');
    }

    // return user who made the request account
    private function getAccount($apikey) {
        $_account = $this->app->storage->findOne('cockpit/accounts', ['api_key'  => $apikey]);
        return $_account;
    }

    // return all users, for privacy and senseful security, unset password, api_key and reset token
    private function returnUsers() {
        $options = \array_merge(['sort' => ['user' => 1]], $this->param('options', []));
        $accounts = $this->app->storage->find('cockpit/accounts', $options)->toArray();

        foreach ($accounts as &$account) {
            unset($account['password'], $account['api_key'], $account['_reset_token']);
        }
        return $accounts;
    }

    // Main Func
    public function downloadCsv() {
        $apikey = $this->param('token');
        $_account = $this->getAccount($apikey);

        // Security Reason unset password
        unset($_account['password']);

        // Is user admin?
        $isAdmin = $this->module('cockpit')->isSuperAdmin($_account['group']);

        if (!$isAdmin) {
            return $this->stop(401);
        }

        if ( $_account && $isAdmin ) {
            if (!$this->module('cockpit')->hasaccess('cockpit', 'accounts')) {
                return $this->stop(401);
            }
            $this->setHeaders();
            $accounts = $this->returnUsers();

            $i = 1;
            $user_CSV = [];
            $user_CSV[0] = array(
                'name',
                'username',
                'e-mail'
            );

            foreach ($accounts as &$account) {
                $user_CSV[$i] = array(
                    $account['name'],
                    $account['username'],
                    $account['email']
                );
                $i++;
            }
            
            $fp = fopen('php://output', 'wb');
            foreach ($user_CSV as $line) {
                // though CSV stands for "comma separated value"
                // in many countries (including France) separator is ";"
                fputcsv($fp, $line, ',');
            }
            fclose($fp);
            return '';
        }

        return [];
    }

}
## Export users as CSV

Make avaialable custom API ( with Token and Super admin required ) to export Users to CSV.


### Usage

Install as addon, then just point to: #COCKPITROOT#/api/users/downloadCsv?token={adminToken}

It will directly download a CSV File with all users


### Customize

It does not have any configuration, you need to edit code to custom fields, you can customize inside RestApiFiles.php main func

<pre>
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
</pre>
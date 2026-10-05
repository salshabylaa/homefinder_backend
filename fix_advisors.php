<?php
$users = DB::table('users')->where('role', 'homeadvisor')->whereNull('phone_number')->get();
foreach($users as $user) {
    $app = DB::table('applications')->where('email', $user->email)->orderBy('id', 'desc')->first();
    if($app && $app->nomor_wa) {
        DB::table('users')->where('id', $user->id)->update(['phone_number' => $app->nomor_wa]);
    }
}
echo "Fixed existing advisors.\n";

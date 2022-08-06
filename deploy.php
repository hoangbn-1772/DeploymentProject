<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'Deployment_Project');

// Project repository
set('repository', 'git@github.com:hoangbn-1772/DeploymentProject.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false); 

// Shared files/dirs between deploys 
add('shared_files', ['.env']);
add('shared_dirs', [
    'storage',
    'bootstrap/cache',
    'public/racing-game-cocos',
    'public/new-racing-scene',
    'public/coloring-game',
    '.credentials',
    'public/coloring',
]);

// Writable dirs by web server 
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);


// Hosts
inventory('hosts.yml'); 
    
// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});
task('yarn:run:install', function () {
    run('cd {{release_path}} && yarn install');
});
task('yarn:run:prod', function () {
    run('cd {{release_path}} && yarn prod');
});

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'yarn:run:install',
    'yarn:run:prod',
    'deploy:writable',
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:optimize',
    'deploy:clear_paths',
    'artisan:storage:link',
    'artisan:migrate',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
after('deploy', 'success');
desc('Deploy done!');

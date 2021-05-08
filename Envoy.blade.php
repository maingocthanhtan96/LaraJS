@servers(['local' => '127.0.0.1', 'web' => ['ec2-user@35.73.122.213']])

@setup
    $now = new DateTime();
    $branch = isset($branch) ? $branch : 'uat';
    $repository = 'git@gitlab.com:maingocthanhtan96/larajs.git';
    $original_dir = '/var/www/larajs';
    $releases_dir = $original_dir . '/release';
    $app_dir = $original_dir . '/app';
    $release = $branch . '-' . date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy', ['on' => 'web'])
clone_repository
    run_composer
    update_symlinks
    run_deploy_scripts
    delete_git_metadata
    clean_old_releases
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone {{ $repository }} --branch={{ $branch }} --depth=1 -q {{ $new_release_dir }}
    cd {{ $new_release_dir }}
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $new_release_dir }}
    echo "Running composer..."
    php composer.phar install --prefer-dist --no-scripts -q -o
@endtask

@task('update_symlinks')
    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

    echo 'Linking storage directory'
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

    {{--    echo 'Linking vendor release'--}}
    {{--    ln -nfs {{ $app_dir }}/vendor {{ $new_release_dir }}/vendor--}}

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $original_dir }}/current
@endtask

@task('run_deploy_scripts')
    echo 'Running deployment scripts'
    cd {{ $new_release_dir }}
    php artisan optimize
    php artisan migrate --force
@endtask

@task('delete_git_metadata')
    echo 'Delete .git folder'
    cd {{ $new_release_dir }}
    rm -rf .git
@endtask

@task('clean_old_releases')
    # This will list our releases by modification time and delete all but the 2 most recent.
    purging=$(ls -dt {{ $releases_dir }}/* | tail -n +3);

    if [ "$purging" != "" ]; then
        echo Purging old releases: $purging;
        rm -rf $purging;
        else
        echo 'No releases found for purging at this time';
    fi
@endtask

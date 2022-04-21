<?php

namespace App\Traits;

use Symplify\GitWrapper\GitWrapper;
use Illuminate\Support\Facades\Storage;

trait GitTrait
{
    public function initializeGit()
    {
        $gitWrapper = new GitWrapper(config('git.binary'));
        $gitWrapper->setPrivateKey(config('git.ssh_key'));

        if (!is_dir(config('git.local'))) {
            $git = $gitWrapper->cloneRepository(config('git.remote'), config('git.local'), ['b' => config('git.remote_branch')]);
        } else {
            $git = $gitWrapper->workingCopy(config('git.local'));
            $git->pull();
        }

        $git->config('user.name', config('git.user_name'));
        $git->config('user.email', config('git.user_email'));
        $git->config('commit.gpgsign', 'false');

        return $git;
    }

    public function trimWhiteSpaces(String $file)
    {
        $content = Storage::disk('git')->get($file);
        $content = trim(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content));
        Storage::disk('git')->put($file, $content);
    }

    public function fqdn(String $uri)
    {
        $part = preg_replace('#^https?://#', '', $uri);
        $slash = strpos($part, '/');
        if ($slash) {
            return substr($part, 0, $slash);
        } else {
            return $part;
        }
    }

    public function committer()
    {
        return strtolower(config('app.name'));
    }
}

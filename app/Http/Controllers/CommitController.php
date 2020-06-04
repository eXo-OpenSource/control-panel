<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GrahamCampbell\GitLab\GitLabManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(GitlabManager $gitlabManager)
    {
        $allowedProjects = [
            3 => 'MTA Script', // MTA
            95 => 'Control Panel', // CP
        ];

        $selectedProject = 3;

        $avatarToEmail = [];
        if(Cache::has('gitlab:users')) {
            $avatarToEmail = Cache::get('gitlab:users');
        } else {
            $users = $gitlabManager->users()->all(['per_page' => 100]);

            foreach($users as $user)
            {
                $avatarToEmail[$user['email']] = $user['avatar_url'];
                $emails = $gitlabManager->users()->userEmails($user['id']);
                foreach($emails as $email)
                {
                    $avatarToEmail[$email['email']] = $user['avatar_url'];
                }
            }

            Cache::put('gitlab:users', $avatarToEmail, Carbon::now()->addHours(8));
        }

        if(request()->has('project') && isset($allowedProjects[intval(request()->get('project'))])) {
            $selectedProject = intval(request()->get('project'));
        }

        $commits = [];

        if(!Cache::has('gitlab:commits:' . $selectedProject))
        {
            $branches = $gitlabManager->repositories()->branches($selectedProject, ['per_page' => 100]);

            $allCommits = [];

            foreach($branches as $branch)
            {
                if(Carbon::parse($branch['commit']['committed_date']) > Carbon::now()->subMonths(6)) // ignore branches without a commit in the last 6 months
                {
                    $realCommits = $gitlabManager->repositories()->commits($selectedProject, ['ref_name' => $branch['name'], 'per_page' => 100]);
                    foreach($realCommits as $commit)
                    {
                        $commit['branch'] = $branch['name'];
                        array_push($allCommits, $commit);
                    }
                }
            }


            usort($allCommits, function($a, $b) {
                return $a['committed_date'] < $b['committed_date'];
            });

            $knownHashes = [];
            for($i = count($allCommits) - 1; $i >= 0; $i--)
            {
                if(isset($knownHashes[$allCommits[$i]['id']]))
                {
                    unset($allCommits[$i]);
                }
                else
                {
                    $knownHashes[$allCommits[$i]['id']] = true;
                }
            }

            $allCommits = collect($allCommits);

            $blockElements = ['▀', '▁', '▂', '▃', '▄', '▅', '▆', '▇', '█', '▉',
                '▊', '▋', '▌', '▍', '▎', '▏', '▐', '▔', '▕', '▖',
                '▗', '▘', '▙', '▚', '▛', '▜', '▝', '▞', '▟'];

            foreach($allCommits->take(100) as $message) {

                $orgCommitMessage = $message['title'];
                $commitMessage = '';
                $orgBranch = $message['branch'];
                $branch = '';

                if(str_contains(strtolower($orgCommitMessage), '[hide]') || str_starts_with(strtolower($orgBranch), 'hide/'))
                {
                    mt_srand(crc32($message['push_data']['commit_to']));
                    for($i = 1; $i < strlen($orgCommitMessage); $i++) {
                        $commitMessage .= $blockElements[mt_rand(0, count($blockElements) - 1)];
                    }
                    if(str_starts_with(strtolower($orgBranch), 'hide/')) {
                        for($i = 1; $i < strlen($orgBranch); $i++) {
                            $branch .= $blockElements[mt_rand(0, count($blockElements) - 1)];
                        }
                    } else {
                        $branch = $orgBranch;
                    }
                    mt_srand();
                }
                else
                {
                    $branch = $orgBranch;
                    $commitMessage = $orgCommitMessage;
                }

                array_push($commits, [
                    'short_id' => $message['short_id'],
                    'author' => $message['committer_name'],
                    'avatar' => isset($avatarToEmail[$message['committer_email']]) ? $avatarToEmail[$message['committer_email']] : '',
                    'date' => \Carbon\Carbon::parse($message['committed_date']),
                    'branch' => $branch,
                    'commit' => $commitMessage,
                ]);
            }

            Cache::put('gitlab:commits:' . $selectedProject, $commits, Carbon::now()->addHour());
        }
        else
        {
            $commits = Cache::get('gitlab:commits:' . $selectedProject);
        }

        return view('commits.index', compact('commits', 'allowedProjects', 'selectedProject'));
    }
}

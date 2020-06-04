<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitLab\GitLabManager;
use Illuminate\Http\Request;

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

        if(request()->has('project') && isset($allowedProjects[intval(request()->get('project'))])) {
            $selectedProject = intval(request()->get('project'));
        }

        $messages = $gitlabManager->projects()->events($selectedProject, ['action' => 'pushed', 'per_page' => 100]);

        $blockElements = ['▀', '▁', '▂', '▃', '▄', '▅', '▆', '▇', '█', '▉',
            '▊', '▋', '▌', '▍', '▎', '▏', '▐', '▔', '▕', '▖',
            '▗', '▘', '▙', '▚', '▛', '▜', '▝', '▞', '▟'];

        $commits = [];

        foreach($messages as $message) {

            $orgCommitMessage = $message['push_data']['commit_title'];
            $commitMessage = '';
            $orgBranch = $message['push_data']['ref'];
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
                'author' => $message['author_username'],
                'date' => \Carbon\Carbon::parse($message['created_at']),
                'branch' => $branch,
                'commit' => $commitMessage,
            ]);
        }


        return view('commits.index', compact('commits', 'allowedProjects', 'selectedProject'));
    }
}

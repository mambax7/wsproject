<table class="outer">
    <tr>
        <th><a href="?op=listprojects&amp;order=<{if $data.sort != 'nameup'}>nameup<{else}>namedown<{/if}>" class="itemHead" title="A-Z"><b><{$lang.projectname}></b></a></th>
        <th><b><{$lang.description}></b></th>
        <th><b><{$lang.progress}></b>
        </td>
        <th><a href="?op=listprojects&amp;order=<{if $data.sort != 'deadlineup'}>deadlineup<{else}>deadlinedown<{/if}>" class="itemHead" title="&lt;"><b><{$lang.deadline}></b></a>
        </td>
        <th><b><{$lang.action}></b></th>
    </tr>
    <{foreach from=$data.projects item=project}>
    <{if $project.project_id != ''}>
    <tr>
        <td class="even">
            <{if $project.user.projectuser }>
            <b><a href="?op=showproject&amp;project_id=<{$project.project_id}>"><{$project.name}></a></b>
            <{else}>
            <b><{$project.name}></b>
            <{/if}>
        </td>
        <td class="odd"><{$project.description}></td>
        <td nowrap="nowrap" class="odd">
            <table style="border:0px;padding:0px;margin:0px;">
                <tr>
                    <td><{$project.timeinfo}></td>
                    <td><{$project.timebar}></td>
                </tr>
                <tr>
                    <td><{$project.workinfo}></td>
                    <td><{$project.progressbar}></td>
                </tr>
            </table>
        </td>
        <td nowrap="nowrap" class="odd" style="text-align:center;"><{$project.enddate}></td>
        <td class="even">
            <table style="border:0px;padding:0px;margin:0px;">
                <{if $project.user.projectadmin or $data.user.admin }>
                <tr>
                    <td nowrap="nowrap">[<a href="?op=addtask&amp;project_id=<{$project.project_id}>"><{$lang.addtask}></a>]</td>
                </tr>
                <{else}>
                <tr>
                    <td nowrap="nowrap">[<{$lang.addtask}>]</td>
                </tr>
                <{/if}>
                <{if $data.user.admin }>
                <tr>
                    <td nowrap="nowrap">[<a href="?op=editproject&amp;project_id=<{$project.project_id}>"><{$lang.edit}></a>]</td>
                </tr>
                <tr>
                    <td nowrap="nowrap">[<a href="?op=deleteproject&amp;project_id=<{$project.project_id}>"><{$lang.delete}></a>]</td>
                </tr>
                <{/if}>
            </table>
        </td>
    </tr>
    <{/if}>
    <{/foreach}>
</table>
<br/>
<{include file='db:system_notification_select.tpl'}>

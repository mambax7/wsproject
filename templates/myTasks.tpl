<table class="outer">
    <tr>
        <th colspan="2"><{$lang.userdetails}> <b><{$data.name}></b>
        </td>
    </tr>
    <tr>
        <td class="head" style="width:30%;text-align:right;"><{$lang.username}>:</td>
        <td class="even" style="width:70%;text-align:left;"><b><{$data.uname}></b></td>
    </tr>
    <tr>
        <td class="head" style="width:30%;text-align:right;"><{$lang.rank}>:</td>
        <td class="even" style="width:70%;text-align:left;"><b><{$data.rank}></b></td>
    </tr>
    <tr>
        <td class="head" style="width:30%;text-align:right;"><{$lang.projects}>:</td>
        <td class="even" style="width:70%;text-align:left;"><b><{$data.project_count}></b></td>
    </tr>
    <tr>
        <td class="head" style="width:30%;text-align:right;"><{$lang.opentasks}>:</td>
        <td class="even" style="width:70%;text-align:left;"><b><{$data.task_count}></b></td>
    </tr>
</table>
<br/>
<{foreach from=$data.projects item=project}>
<table class="outer">
    <tr>
        <th style="width:40%;">
            <{$lang.project}>: <b><a class="itemHead" href="?op=showproject&project_id=<{$project.project_id}>"><{$project.name}></a></b>
        </th>
        <th nowrap="nowrap" style="text-align:center;width:60%;">
            <{if $project.user.projectadmin }>
            [<a class="itemHead" href="?op=addtask&amp;project_id=<{$project.project_id}>"><{$lang.addtask}></a>]
            <{else}>
            [<{$lang.addtask}>]
            <{/if}>
            <{if $data.user.admin }>
            :: [<a class="itemHead" href="?op=editproject&amp;project_id=<{$project.project_id}>"><{$lang.edit}></a>]
            :: [<a class="itemHead" href="?op=deleteproject&amp;project_id=<{$project.project_id}>"><{$lang.delete}></a>]
            <{/if}>
        </th>
    </tr>
    <tr class="head">
        <td nowrap="nowrap" style="text-align:right;" colspan="2">
            <table style="border:0px;padding:0px;margin:0px;">
                <tr>
                    <td><{$project.timeinfo}></td>
                    <td style="width:300px;"><{$project.timebar}></td>
                </tr>
                <tr>
                    <td><{$project.workinfo}></td>
                    <td style="width:300px;"><{$project.progressbar}></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="outer">
    <tr class="head">
        <td style="text-align:center;width:8%;"><{$lang.hours|upper}></td>
        <td style="text-align:center;width:8%;"><{$lang.status|upper}></td>
        <td style="text-align:center;width:24%;"><{$lang.title|upper}></td>
        <td style="text-align:center;width:55%"><{$lang.description|upper}></td>
        <td style="text-align:center;width:5%;"><{$lang.action|upper}></td>
    </tr>
    <{foreach from=$project.tasks item=task}>
    <tr class="even">
        <td style="text-align:center;"><{$task.hours}></td>
        <td style="text-align:center;"><{$task.status}>%</td>
        <td><a href="?op=showtask&task_id=<{$task.task_id}>"><{$task.title}></a></td>
        <td><{$task.description}>&nbsp;</td>
        <td align="center" nowrap="nowrap">
            <a href="?op=showproject&amp;subop=finishtask&amp;task_id=<{$task.task_id}>&amp;project_id=<{$project.project_id}>"><img src="assets/images/f-on.gif" border="0"></a>
            <{if $project.user.projectadmin }>
            <a href="?op=deletetask&amp;task_id=<{$task.task_id}>" alt="<{$lang.delete}>"><img src="assets/images/d-on.gif" border="0"></a>
            <{/if}>
        </td>
    </tr>
    <{/foreach}>
</table>
<br/>
<{/foreach}>
<br/>
<{include file='db:system_notification_select.tpl'}>

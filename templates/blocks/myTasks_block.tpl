<table class="outer">
    <tr>
        <th colspan="2"><{$block.lang.userdetails}> <b><{$block.data.name}></b>
        </td>
    </tr>
    <tr>
        <td class="head" style="width:30%;text-align:right;"><{$block.lang.username}>:</td>
        <td class="even" style="width:70%;text-align:left;"><b><{$block.data.uname}></b></td>
    </tr>
    <tr>
        <td class="head" style="width:30%;text-align:right;"><{$block.lang.rank}>:</td>
        <td class="even" style="width:70%;text-align:left;"><b><{$block.data.rank}></b></td>
    </tr>
    <tr>
        <td class="head" style="width:30%;text-align:right;"><{$block.lang.projects}>:</td>
        <td class="even" style="width:70%;text-align:left;"><b><{$block.data.project_count}></b></td>
    </tr>
    <tr>
        <td class="head" style="width:30%;text-align:right;"><{$block.lang.opentasks}>:</td>
        <td class="even" style="width:70%;text-align:left;"><b><{$block.data.task_count}></b></td>
    </tr>
</table>
<br/>
<{foreach from=$block.data.projects item=project}>
<table class="outer">
    <tr>
        <th style="width:40%;">
            <{$block.lang.project}>: <b><a class="itemHead" href="<{$xoops_url}>/modules/wsproject/?op=showproject&project_id=<{$project.project_id}>"><{$project.name}></a></b>
        </th>
        <th nowrap="nowrap" style="text-align:center;width:60%;">
            <{if $project.user.projectadmin }>
            [<a class="itemHead" href="<{$xoops_url}>/modules/wsproject/?op=addtask&amp;project_id=<{$project.project_id}>"><{$block.lang.addtask}></a>]
            <{else}>
            [<{$block.lang.addtask}>]
            <{/if}>
            <{if $block.data.user.admin }>
            :: [<a class="itemHead" href="<{$xoops_url}>/modules/wsproject/?op=editproject&amp;project_id=<{$project.project_id}>"><{$block.lang.edit}></a>]
            :: [<a class="itemHead" href="<{$xoops_url}>/modules/wsproject/?op=deleteproject&amp;project_id=<{$project.project_id}>"><{$block.lang.delete}></a>]
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
        <td style="text-align:center;width:8%;"><{$block.lang.hours|upper}></td>
        <td style="text-align:center;width:8%;"><{$block.lang.status|upper}></td>
        <td style="text-align:center;width:24%;"><{$block.lang.title|upper}></td>
        <td style="text-align:center;width:55%"><{$block.lang.description|upper}></td>
        <td style="text-align:center;width:5%;"><{$block.lang.action|upper}></td>
    </tr>
    <{foreach from=$project.tasks item=task}>
    <tr class="even">
        <td style="text-align:center;"><{$task.hours}></td>
        <td style="text-align:center;"><{$task.status}>%</td>
        <td><a href="<{$xoops_url}>/modules/wsproject/?op=showtask&task_id=<{$task.task_id}>"><{$task.title}></a></td>
        <td><{$task.description}>&nbsp;</td>
        <td align="center" nowrap="nowrap">
            <a href="<{$xoops_url}>/modules/wsproject/?op=showproject&amp;subop=finishtask&amp;task_id=<{$task.task_id}>&amp;project_id=<{$project.project_id}>"><img src="<{$xoops_url}>/modules/wsproject/assets/images/f-on.gif" border="0"></a>
            <{if $project.user.projectadmin }>
            <a href="<{$xoops_url}>/modules/wsproject/?op=deletetask&amp;task_id=<{$task.task_id}>" alt="<{$block.lang.delete}>"><img src="<{$xoops_url}>/modules/wsproject/assets/images/d-on.gif" border="0"></a>
            <{/if}>
        </td>
    </tr>
    <{/foreach}>
</table>
<br/>
<{/foreach}>
<br/>
<{include file='db:system_notification_select.tpl'}>

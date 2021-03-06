<table class="outer">
    <tr>
        <th><b><{$data.name}></b></th>
        <th style="text-align:right;">
            <{if $data.user.projectadmin and ($data.todo == "0" and $data.completed == "0") }>
            [<a class="itemHead" href="?op=showproject&amp;subop=complete&amp;project_id=<{$data.project_id}>"><{$lang.completeproject}></a>] ::
            <{/if}>
            <{if $data.user.projectadmin and ($data.completed == "1") }>
            [<a class="itemHead" href="?op=showproject&amp;subop=reactivate&amp;project_id=<{$data.project_id}>"><{$lang.reactivate}></a>] ::
            <{/if}>
            <{if $data.user.projectadmin }>
            <{if $data.completed == "0" }>
            [<a class="itemHead" href="?op=addtask&amp;project_id=<{$data.project_id}>"><{$lang.addtask}></a>] ::
            <{/if}>
            [<a class="itemHead" href="?op=deleteproject&amp;project_id=<{$data.project_id}>"><{$lang.delete}></a>] ::
            [<a class="itemHead" href="?op=editproject&amp;project_id=<{$data.project_id}>"><{$lang.edit}></a>]
            <{/if}>
        </th>
    </tr>
    <tr class="even">
        <td style="width:50%;"><b><{$lang.projectstart}>:</b> <{$data.startdate}></td>
        <td style="width:50%;"><b><{$lang.projectdeadline}>:</b> <{$data.enddate}></td>
    </tr>
    <tr>
        <td class="even" style="padding:0;">
            <table>
                <tr>
                    <td class="head" style="width:60%;padding:0;">
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;order=<{if $data.sort != 'nameup'}>nameup<{else}>namedown<{/if}>" title="A-Z"><b><{$lang.todo}></b></a>
                    </td>
                    <td class="head" style="width:10%;text-align:center;padding:0;">
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;order=<{if $data.sort != 'userup'}>userup<{else}>userdown<{/if}>" title="A-Z"><b><{$lang.respuser}></b></a>
                    </td>
                    <td class="head" style="width:10%;text-align:center;padding:0;">
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;order=<{if $data.sort != 'statusup'}>statusup<{else}>statusdown<{/if}>" title="A-Z"><b><{$lang.status}></b></a>
                    </td>
                    <td class="head" style="width:10%;text-align:center;padding:0;">
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;order=<{if $data.sort != 'hoursup'}>hoursup<{else}>hoursdown<{/if}>" title="&lt;"><b><{$lang.hours}></b></a>
                    </td>
                    <td class="head" style="width:10%;text-align:center;padding:0;">
                        <b><{$lang.action}></b>
                    </td>
                </tr>
                <{foreach from=$data.tasks item=task}>
                <{if $task.status < 100 or ($task.children != NULL and $task.childrencompleted != "true")}>
                <tr>
                    <td class="even" style="padding:0;padding-left:<{$task.indent}>px;">
                        <a href="?op=showtask&amp;task_id=<{$task.task_id}>" <{if $task.status==100}>class="comDate"<{/if}>><{$task.title}></a>
                    </td>
                    <td class="odd" style="text-align:center;padding:0;"><{$task.uname}></td>
                    <td class="odd" style="text-align:center;padding:0;"><{$task.status}>%</td>
                    <td class="odd" style="text-align:center;padding:0;"><{$task.hours}></td>
                    <td class="odd" style="text-align:center;padding:0;">
                        <{if !$data.user.projectadmin or $task.children != NULL }>
                        <img src="assets/images/d-off.gif" border="0">
                        <{if $task.user.owner and $task.childrencompleted == "true"}>
                        <a href="?op=showproject&amp;subop=finishtask&amp;task_id=<{$task.task_id}>&amp;project_id=<{$data.project_id}>"><img src="assets/images/f-on.gif" border="0"></a>
                        <{else}>
                        <img src="assets/images/f-off.gif" border="0">
                        <{/if}>
                        <{else}>
                        <a href="?op=deletetask&task_id=<{$task.task_id}>"><img src="assets/images/d-on.gif" border="0"></a>
                        <a href="?op=showproject&amp;subop=finishtask&amp;task_id=<{$task.task_id}>&amp;project_id=<{$data.project_id}>"><img src="assets/images/f-on.gif" border="0"></a>
                        <{/if}>
                    </td>
                </tr>
                <{/if}>
                <{/foreach}>
                <tr>
                    <td class="foot" colspan="3"><i><{$lang.hourstodo}></i></td>
                    <td class="foot" align="center"><{$data.todo}></td>
                    <td class="foot">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td class="even" style="padding:0;">
            <table>
                <tr>
                    <td class="head" style="width:60%;padding:0;">
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;order=<{if $data.sort != 'nameup'}>nameup<{else}>namedown<{/if}>" title="A-Z"><b><{$lang.completedtasks}></b></a>
                    </td>
                    <td class="head" style="width:10%;padding:0;text-align:center;">
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;order=<{if $data.sort != 'userup'}>userup<{else}>userdown<{/if}>" title="A-Z"><b><{$lang.respuser}></b></a>
                    </td>
                    <td class="head" style="width:10%;padding:0;text-align:center;">
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;order=<{if $data.sort != 'statusup'}>statusup<{else}>statusdown<{/if}>" title="A-Z"><b><{$lang.status}></b></a>
                    </td>
                    <td class="head" style="width:10%;padding:0;text-align:center;">
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;order=<{if $data.sort != 'hoursup'}>hoursup<{else}>hoursdown<{/if}>" title="&lt;"><b><{$lang.hours}></b></a>
                    </td>
                    <td class="head" style="width:10%;padding:0;text-align:center;">
                        <b><{$lang.action}></b>
                    </td>
                </tr>
                <{foreach from=$data.tasks item=task}>
                <{if $task.status == 100 or $task.somechildrendone == "true"}>
                <tr>
                    <td class="even" style="padding:0;padding-left:<{$task.indent}>px;">
                        <{if $task.childrencompleted == "true" and $task.status == 100 }>
                        <a href="?op=showtask&amp;task_id=<{$task.task_id}>"><{$task.title}></a>
                        <{else}>
                        <a href="?op=showtask&amp;task_id=<{$task.task_id}>" class="comDate"><{$task.title}></a>
                        <{/if}>
                    </td>
                    <td class="odd" style="padding:0;"><{$task.uname}></td>
                    <td class="odd" style="text-align:center;padding:0;"><{$task.status}>%</td>
                    <td class="odd" style="text-align:center;padding:0;"><{$task.hours}></td>
                    <td class="odd" style="text-align:center;padding:0;">
                        <{if !$data.user.projectadmin or $task.children != NULL }>
                        <img src="assets/images/d-off.gif" border="0">
                        <{if $task.user.owner and ($task.childrencompleted == "true" and $task.status == 100) }>
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;subop=restarttask&amp;task_id=<{$task.task_id}>"><img src="assets/images/r-on.gif" border="0"></a>
                        <{else}>
                        <img src="assets/images/r-off.gif" border="0">
                        <{/if}>
                        <{else}>
                        <a href="?op=deletetask&task_id=<{$task.task_id}>"><img src="assets/images/d-on.gif" border="0"></a>
                        <a href="?op=showproject&amp;project_id=<{$data.project_id}>&amp;subop=restarttask&amp;task_id=<{$task.task_id}>"><img src="assets/images/r-on.gif" border="0"></a>
                        <{/if}>
                    </td>
                </tr>
                <{/if}>
                <{/foreach}>
                <tr>
                    <td class="foot" colspan="3"><i><{$lang.hoursdone}></i></td>
                    <td class="foot" style="text-align:center;"><{$data.done}></td>
                    <td class="foot">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="foot">
            <table style="margin:0px;padding:0px;">
                <tr>
                    <td><{$data.timeinfo}></td>
                    <td><{$data.timebar}></td>
                </tr>
                <tr>
                    <td><{$data.workinfo}></td>
                    <td><{$data.progressbar}></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br/>
<{include file='db:system_notification_select.tpl'}>

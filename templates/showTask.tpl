<table class="outer">
    <tr>
        <th colspan="2"><a href="?op=showproject&amp;project_id=<{$data.project_id}>" class="itemHead"><b><{$lang.showproject}></b></a>
        </td>
    </tr>
    <tr>
        <td style="width:38%;text-align:right;" class="head">
            <b><{$lang.projectname}>: </b>
        </td>
        <td class="even">
            <a href="?op=showproject&amp;project_id=<{$data.project_id}>"><{$data.project_name}></a>
        </td>
    </tr>
    <tr>
        <td style="width:38%;text-align:right;" class="head">
            <b><{$lang.taskname}>: </b>
        </td>
        <td class="even">
            <{$data.title}>
        </td>
    </tr>
    <tr>
        <td style="width:38%;text-align:right;" class="head">
            <b><{$lang.assignedto}>: </b>
        </td>
        <td class="even">
            <{$data.uname}>
        </td>
    </tr>
    <tr>
        <td style="width:38%;text-align:right;" class="head"><b><{$lang.quotedhours}>: </b></td>
        <td class="even"><{$data.hours}></td>
    </tr>
    <tr>
        <td style="width:38%;text-align:right;" class="head"><b><{$lang.status}>: </b></td>
        <td class="even"><{$data.status}>%</td>
    </tr>
    <tr>
        <td style="width:38%;text-align:right;" class="head"><b><{$lang.public}>: </b></td>
        <td class="even">
            <{if $data.public == "0" }>
            <input type="checkbox" readonly="readonly"/>
            <{else}>
            <input type="checkbox" readonly="readonly" checked="checked"/>
            <{/if}>
        </td>
    </tr>
    <{if $data.parent_id != 0 }>
    <tr>
        <td style="width:38%;text-align:right;" class="head"><b><{$lang.subtaskof}>: </b></td>
        <td class="even">
            <a href="?op=showtask&amp;task_id=<{$data.parent_id}>"><{$data.parent.title}></a>
        </td>
    </tr>
    <{/if}>
    <tr valign="top">
        <td style="width:38%;text-align:right;" class="head"><b><{$lang.description}>: </b></td>
        <td class="even"><{$data.description}></td>
    </tr>
    <{if isset($data.children)}>
    <tr valign="top">
        <td style="width:38%;text-align:right;" class="head">
            <b><{$lang.subtasks}>: </b>
        </td>
        <td class="even">
            <{foreach from=$data.children item=child}>
            <div><a href="?op=showtask&amp;task_id=<{$child.task_id}>"><{$child.title}></a></div>
            <{/foreach}>
        </td>
    </tr>
    <{/if}>
    <tr>
        <td class="foot">&nbsp;</td>
        <td class="foot">
            <{if $data.user.owner }>
            <form name="editTask" method="post" action="index.php" style="padding:0px;margin:0px;">
                <input type="hidden" name="op" value="edittask"/>
                <input type="hidden" name="task_id" value="<{$data.task_id}>"/>
                <input type="submit" name="submit" value="<{$lang.edit}>"/>
            </form>
            <{else}>
            &nbsp;
            <{/if}>
        </td>
    </tr>
</table>
<br/>
<div style="text-align: center; padding: 3px; margin: 3px;">
    <{$commentsnav}>
    <{$lang_notice}>
</div>

<div style="margin: 3px; padding: 3px;">
    <!-- start comments loop -->
    <{if $comment_mode == "flat"}>
    <{include file="db:system_comments_flat.tpl"}>
    <{elseif $comment_mode == "thread"}>
    <{include file="db:system_comments_thread.tpl"}>
    <{elseif $comment_mode == "nest"}>
    <{include file="db:system_comments_nest.tpl"}>
    <{/if}>
    <!-- end comments loop -->
</div>
<br/>
<{include file='db:system_notification_select.tpl'}>

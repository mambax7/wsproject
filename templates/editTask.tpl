<form name="editTask" method="post" action="index.php" style="padding:0px;margin:0px;">
    <table class="outer">
        <tr>
            <th colspan="2">
                <a href="?op=showtask&amp;task_id=<{$data.task_id}>" class="itemHead"><b><{$lang.back}></b></a>
                <input type="hidden" name="op" value="showtask"/>
                <input type="hidden" name="subop" value="save"/>
                <input type="hidden" name="task_id" value="<{$data.task_id}>"/>
            </th>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.projectname}>: </b></td>
            <td class="even">
                <a href="?op=showproject&amp;project_id=<{$data.project_id}>"><{$data.project_name}></a>
            </td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.taskname}>: </b></td>
            <td class="even">
                <{if $data.user.projectadmin }>
                <input name="title" type="text" size="40" maxlength="100" value="<{$data.title}>"/>
                <{else}>
                <{$data.title}><input name="title" type="hidden" value="<{$data.title}>"/>
                <{/if}>
            </td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.assignedto}>: </b></td>
            <td class="even">
                <{if $data.user.projectadmin }>
                <select name="user_id">
                    <option value="<{$data.user_id}>"><{$data.user_name}></option>
                    <option value="<{$user.uid}>">---------</option>
                    <{foreach from=$data.users item=user}>
                    <option value="<{$user.uid}>"><{$user.uname}></option>
                    <{/foreach}>
                </select>
                <{else}>
                <{$data.user_name}><input name="user_id" type="hidden" value="<{$user.uid}>"/>
                <{/if}>
            </td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.quotedhours}>: </b></td>
            <td class="even"><input name="hours" type="text" size="40" maxlength="100" value="<{$data.hours}>"/></td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.status}>: </b></td>
            <td class="even">
                <select name="status">
                    <option value="<{$data.status}>"><{$data.status}>%</option>
                    <option value="">----</option>
                    <option value="0">0%</option>
                    <option value="10">10%</option>
                    <option value="20">20%</option>
                    <option value="30">30%</option>
                    <option value="40">40%</option>
                    <option value="50">50%</option>
                    <option value="60">60%</option>
                    <option value="70">70%</option>
                    <option value="80">80%</option>
                    <option value="90">90%</option>
                    <option value="100">100%</option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.public}>: </b></td>
            <td class="even">
                <{if $data.user.projectadmin }>
                <input type="checkbox" name="public" value="true" <{if $data.public == "1" }>checked="checked"<{/if}> />
                <{else}>
                <input type="checkbox" name="public" value="true" readonly="readonly" <{if $data.public == "1" }>checked="checked"<{/if}> />
                <{/if}>
            </td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.subtaskof}>: </b></td>
            <td class="even">
                <{if $data.user.projectadmin }>
                <select name="parent_id">
                    <option value="<{$data.parent_id}>"><{$data.parent_name}></option>
                    <option value="0">------------</option>
                    <option value="0"><{$lang.toplevel}></option>
                    <option value="0">------------</option>
                    <{foreach from=$data.tasks item=task}>
                    <option value="<{$task.task_id}>"><{$task.title|indent:$task.indent:"&nbsp;"}></option>
                    <{/foreach}>
                </select>
                <{else}>
                <{$data.parent_name}><input name="parent_id" type="hidden" value="<{$task.task_id}>"/>
                <{/if}>
            </td>
        </tr>
        <tr valign="top">
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.description}>: </b></td>
            <td class="even">
                <{if $data.user.projectadmin }>
                <textarea name="description" cols="40" rows="7" id="description"><{$data.description}></textarea>
                <{else}>
                <{$data.description}><input name="description" type="hidden" value="<{$data.description}>"/>
                <{/if}>
            </td>
        </tr>
        <tr>
            <td class="foot">&nbsp;</td>
            <td class="foot"><input name="reset" type="reset" class="button" value="<{$lang.restore}>"> <input type="submit" name="submit" value="<{$lang.updatetask}>" class="button"/></td>
        </tr>
    </table>
</form>

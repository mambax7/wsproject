<form action="index.php" method="post" name="addProject" style="padding:0px;margin:0px;">
    <table class="outer">
        <tr>
            <th colspan="2"><a class="itemHead" href="?op=listprojects"><b><{$lang.projectoverview}></b></a></th>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><{$lang.projectname}>:</td>
            <td class="even"><input value="" size="40" name="name" type="text"/></td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><{$lang.startdate}>:</td>
            <td class="even">
                <select name="startmonth" size="1">
                    <{foreach from=$data.month item=month key=mm }>
                    <{if $mm != 'current' }>
                    <option value="<{$mm}>"
                    <{if $data.month.current == $month }>selected="selected"<{/if}> ><{$month}></option>
                    <{/if}>
                    <{/foreach}>
                </select>
                <select name="startday" size="1">
                    <{foreach from=$data.day item=day key=dd }>
                    <{if $dd != 'current' }>
                    <option value="<{$dd}>"
                    <{if $data.day.current == $day }>selected="selected"<{/if}> ><{$day}></option>
                    <{/if}>
                    <{/foreach}>
                </select>
                <select name="startyear" size="1">
                    <{foreach from=$data.year item=year key=yy }>
                    <{if $yy != 'current' }>
                    <option value="<{$yy}>"
                    <{if $data.year.current == $year }>selected="selected"<{/if}> ><{$year}></option>
                    <{/if}>
                    <{/foreach}>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><{$lang.deadline}>:</td>
            <td class="even">
                <select name="endmonth" size="1">
                    <{foreach from=$data.month item=month key=mm }>
                    <{if $mm != 'current' }>
                    <option value="<{$mm}>"
                    <{if $data.month.current == $month }>selected="selected"<{/if}> ><{$month}></option>
                    <{/if}>
                    <{/foreach}>
                </select>
                <select name="endday" size="1">
                    <{foreach from=$data.day item=day key=dd }>
                    <{if $dd != 'current' }>
                    <option value="<{$dd}>"
                    <{if $data.day.current == $day }>selected="selected"<{/if}> ><{$day}></option>
                    <{/if}>
                    <{/foreach}>
                </select>
                <select name="endyear" size="1">
                    <{foreach from=$data.year item=year key=yy }>
                    <{if $yy != 'current' }>
                    <option value="<{$yy}>"
                    <{if $data.year.current == $year }>selected="selected"<{/if}> ><{$year}></option>
                    <{/if}>
                    <{/foreach}>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <td style="width:38%;text-align:right;" class="head"><{$lang.comments}>:</td>
            <td class="even"><textarea cols="40" rows="4" name="description"></textarea></td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.projectadmin}>: </b><br/><span style="font-weight: normal;"><{$lang.projectadminnote}></span></td>
            <td class="even">
                <select name="projectadmin_groups[]" multiple="multiple" size="5">
                    <{foreach from=$data.groups item=title key=id}>
                    <option value="<{$id}>"><{$title}></option>
                    <{/foreach}>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:38%;text-align:right;" class="head"><b><{$lang.projectuser}>: </b><br/><span style="font-weight: normal;"><{$lang.projectusernote}></span></td>
            <td class="even">
                <select name="projectuser_groups[]" multiple="multiple" size="5">
                    <{foreach from=$data.groups item=title key=id}>
                    <option value="<{$id}>"><{$title}></option>
                    <{/foreach}>
                </select>
            </td>
        </tr>
        <tr>
            <td class="foot">&nbsp;</td>
            <td class="foot"><input type="submit" value="<{$lang.addproject}>"></td>
        </tr>
    </table>
    <input type="hidden" name="op" value="listprojects">
    <input type="hidden" name="subop" value="add">
</form>

<table class="outer">
    <tr>
        <th>
            <a href="?op=listcompletedprojects&amp;order=<{if $data.sort != 'nameup'}>nameup<{else}>namedown<{/if}>" class="itemHead"><b><{$lang.projectname}></b></a>
        </th>
        <th><b><{$lang.description}></b></th>
        <th>
            <a href="?op=listcompletedprojects&amp;order=<{if $data.sort != 'endup'}>endup<{else}>enddown<{/if}>" class="itemHead"><b><{$lang.completed}></b></a>
        </th>
        <th align="center"><b><{$lang.hours}></b></th>
        <th align="center"><b><{$lang.action}></b></th>
    </tr>
    <{foreach from=$data.projects item=project}>
    <tr>
        <td class="even">
            <{if $project.user.projectuser }>
            <b><a href="?op=showproject&amp;project_id=<{$project.project_id}>"><{$project.name}></a></b>
            <{else}>
            <b><{$project.name}></b>
            <{/if}>
        </td>
        <td class="odd">
            <{$project.description}>
        </td>
        <td class="odd" style="text-align:center" ;>
            <{$project.completed_date}>
        </td>
        <td class="odd" style="text-align:center" ;>
            <{$project.done}>
        </td>
        <td nowrap="nowrap" class="even" style="text-align:center" ;>
            <{if $project.user.admin }>
            [<a href="?op=showproject&amp;subop=reactivate&amp;project_id=<{$project.project_id}>"><{$lang.reactivate}></a>] ::
            [<a href="?op=deleteproject&amp;project_id=<{$project.project_id}>"><{$lang.delete}></a>] ::
            [<a href="?op=editproject&amp;project_id=<{$project.project_id}>"><{$lang.edit}></a>]
            <{/if}>
        </td>
    </tr>
    <{/foreach}>
</table>

<table class="outer">
    <tr>
        <td class="head" style="text-align:center;">
            <{$lang.deltask}>: <b><{$data.title}></b>?
        </td>
    </tr>
    <{if $data.haschildren }>
    <tr>
        <td class="even" style="text-align:center;">
            <div class="errorMsg"><b><{$lang.warning|upper}></b>
                <p><{$lang.childwarning}></p></div>
        </td>
    </tr>
    <{/if}>
    <tr>
        <td style="text-align:center;" class="foot">
            <a href="?op=showproject&amp;subop=deletetask&amp;project_id=<{$data.project_id}>&amp;task_id=<{$data.task_id}>">[<{$lang.yes|upper}>]</a> :: <a href="?op=showproject&amp;project_id=<{$data.project_id}>">[<{$lang.no|upper}>]</a>
        </td>
    </tr>
</table>

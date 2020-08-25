{capture name="mainbox"}

    {include file="common/pagination.tpl" save_current_page=true save_current_url=true}

    {if $logs}

        <div class="table-responsive-wrapper">
            <table class="table table-middle table--relative table-responsive">
            <tr>
                <th>{__("addons.order_state_history.order_id")}</th>
                <th>{__("addons.order_state_history.previous_status")}</th>
                <th>{__("addons.order_state_history.new_status")}</th>
                <th>{__("addons.order_state_history.changed_by")}</th>
                <th>{__("addons.order_state_history.date_of_change")}</th>
            </tr>
            {foreach from=$logs item="log"}
                <tr>
                    <td data-th="{__("addons.order_state_history.order_id")}">{$log.order_id}</td>
                    <td data-th="{__("addons.order_state_history.previous_status")}">{$log.status_from_name}</td>
                    <td data-th="{__("addons.order_state_history.new_status")}">{$log.status_to_name}</td>
                    <td data-th="{__("addons.order_state_history.changed_by")}">
                        {if $log.user_id}
                            <a href="{"profiles.update?user_id=`$log.user_id`"|fn_url}">
                                {$log.lastname} {$log.firstname}
                            </a>
                        {/if}
                    </td>
                    <td data-th="{__("addons.order_state_history.date_of_change")}">
                        {$log.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
                    </td>
                </tr>
            {/foreach}
            </table>
        </div>
    {else}
        <p class="no-items">{__("no_data")}</p>
    {/if}

    {include file="common/pagination.tpl"}

{/capture}

{include
    file="common/mainbox.tpl"
    title=__("addons.order_state_history.title")
    content=$smarty.capture.mainbox
}
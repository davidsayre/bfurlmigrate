{* DJS @ Custom admin_preview.tpl include *}
{def $lookup_remote_id = $node.object.remote_id} {* Customize remote_id as needed *}
{def $pnd_source_url = bfum_url($lookup_remote_id)}{if $pnd_source_url|count()} <a href="{bfum_url($lookup_remote_id)}">Source link</a>{/if}

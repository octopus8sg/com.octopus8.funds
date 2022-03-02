{crmScope extensionKey='com.octopus8.funds'}
{if $action eq 8}
  {* Are you sure to delete form *}
  <h3>{ts}Delete Category{/ts}</h3>
  <div class="crm-block crm-form-block">
    <div class="crm-section">{ts 1=$myentity.code}Are you sure you wish to delete the Category with Code: %1?{/ts}</div>
  </div>

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{else}

  <div class="crm-block crm-form-block">

    <div class="crm-section">
      <div class="label">{$form.code.label}</div>
      <div class="content">{$form.code.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.name.label}</div>
      <div class="content">{$form.name.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.description.label}</div>
      <div class="content">{$form.description.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>

  </div>
{/if}
{/crmScope}
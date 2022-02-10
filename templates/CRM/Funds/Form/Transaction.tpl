{crmScope extensionKey='com.octopus8.funds'}
{if $action eq 8}
  {* Are you sure to delete form *}
  <h3>{ts}Delete Fund{/ts}</h3>
  <div class="crm-block crm-form-block">
    <div class="crm-section">{ts 1=$myentity.code}Are you sure you wish to delete the Fund with Code: %1?{/ts}</div>
  </div>

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{else}

  <div class="crm-block crm-form-block">

    <div class="crm-section">
      <div class="label">{$form.date.label}</div>
      <div class="content">{$form.date.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.description.label}</div>
      <div class="content">{$form.description.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.amount.label}</div>
      <div class="content">{$form.amount.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.status_id.label}</div>
      <div class="content">{$form.status_id.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.case_id.label}</div>
      <div class="content">{$form.case_id.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.contact_id_sub.label}</div>
      <div class="content">{$form.contact_id_sub.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.contact_id_app.label}</div>
      <div class="content">{$form.contact_id_app.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.component_id.label}</div>
      <div class="content">{$form.component_id.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <div class="label">{$form.account_id.label}</div>
      <div class="content">{$form.account_id.html}</div>
      <div class="clear"></div>
    </div>

    <div class="crm-section">
      <h1>Attachment</h1>
      {include file="CRM/Form/attachment.tpl"}
      <div class="clear"></div>
    </div>


    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>

  </div>
{/if}
{/crmScope}
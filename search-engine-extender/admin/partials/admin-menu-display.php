<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/lopesgon/wordpress-plugins
 * @since      1.0.1
 *
 * @package    Search_Engine_Extender
 * @subpackage search-engine-extender/admin/partials
 */

$excluded_ids = explode(',', get_option('see_excluded_ids'));
?>

<script>
  let isModified = false;

  function isModifiedForm() {
    if (!this.isModified) {
      document.getElementById("see-exclusions-form").exclusionsSubmit.disabled = false;
      this.isModified = true;
    }
  }

  function deleteExclusion(event, id) {
    event.preventDefault();
    const index = getExcludedPostIndex(id);
    if (index !== -1) {
      see_admin_params.excludedPosts.splice(index, 1);
      isModifiedForm();
    }
    document.getElementById("see-" + id).remove();
  }

  function addExclusion(event, form) {
    const id = form.excludedIdValue.value.trim();
    if (id > 0) {
      const index = getExcludedPostIndex(id);
      if (index === -1) {
        see_admin_params.excludedPosts.push(id);
        isModifiedForm();
        createChip(id);
      }
    }
    form.excludedIdValue.value = "";
  }

  function saveChanges(event, form) {
    event.preventDefault();

    let newArrayAsAString = "";
    if (see_admin_params.excludedPosts.length > 0) {
      newArrayAsAString = see_admin_params.excludedPosts[0];
    }
    for (i = 1; i < see_admin_params.excludedPosts.length; i++) {
      if (!!see_admin_params.excludedPosts[i].trim()) {
        newArrayAsAString = see_admin_params.excludedPosts[i].trim() + "," + newArrayAsAString;
      }
    }

    form.see_excluded_ids.value = newArrayAsAString;
    form.submit();
  }

  function getExcludedPostIndex(id) {
    return see_admin_params.excludedPosts.indexOf(id.toString());
  }

  function createChip(id) {
    let element = document.createElement("a");
    element.setAttribute("class", "see-chip");
    element.setAttribute("id", "see-" + id);
    let idElement = document.createElement("div");
    idElement.setAttribute("class", "see-post-id");
    idElement.innerHTML = id;
    let iconElement = document.createElement("span");
    iconElement.setAttribute("class", "dashicons dashicons-no-alt");

    element.appendChild(idElement);
    element.appendChild(iconElement);

    iconElement.addEventListener("click", () => {
      const index = getExcludedPostIndex(id);
      if (index !== -1) {
        see_admin_params.excludedPosts.splice(index, 1);
      }
      document.getElementById("see-" + id).remove();
    });

    document.getElementById("see-chips").appendChild(element);
  }
</script>
<div class="see-container">
  <!-- This file should primarily consist of HTML with a little bit of PHP. -->
  <h1>Search Engine Customizer</h1>
  <form id="see-add-exclusion" method="post" action="javascript:void(0);" onsubmit="addExclusion(event, this)">
    <table class="form-table">
      <tr valign="top">
        <th scope="row">Add new id to be excluded</th>
        <td>
          <input type="number" name="excludedIdValue" />
          <input type="submit" name="addIdSubmit" class="button button-primary" value="Add">
        </td>
      </tr>
    </table>
    <div class="row">
      <div id="see-chips" class="column">
        <?php foreach ($excluded_ids as $value) {
          if (!empty($value)) {
        ?>
            <a id="see-<?php echo $value ?>" class="see-chip" href="<?php echo get_permalink($value) ?>" target="_blank">
              <div class="see-post-id">
                <?php echo $value ?>
              </div>
              <span onclick="deleteExclusion(event, <?php echo $value ?>)" class="dashicons dashicons-no-alt"></span>
            </a>
        <?php }
        } ?>
      </div>
    </div>
  </form>
  <form id="see-exclusions-form" method="post" action="options.php" onsubmit="saveChanges(event, this)">
    <?php settings_fields('search-engine-extender-settings'); ?>
    <?php do_settings_sections('search-engine-extender-settings'); ?>
    <input style="display:none" type="text" name="see_excluded_ids" value="<?php echo get_option('see_excluded_ids'); ?>" />
    <p class="submit see-save">
      <input disabled type="submit" name="exclusionsSubmit" class="see-save button button-primary" value="Save Changes">
    </p>
  </form>
</div>
<?php

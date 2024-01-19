<?php

/*
 * Plugin Name: Custom EMS
 * Description: My plugin to explain the crud functionality.
 * Version: 1.0
 * Author: suhail Academy

 */


function table_creator()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'suhail';
    $sql = "DROP TABLE IF EXISTS $table_name;
             CREATE TABLE $table_name(
             id mediumint(11) NOT NULL AUTO_INCREMENT,
             emp_id varchar(50) NOT NULL,
             emp_name varchar (250) NOT NULL,
             emp_email varchar (250) NOT NULL,
             emp_dept varchar (250) NOT NULL,
             emp_image longtext DEFAULT '',  
             status tinyint(1) NOT NULL DEFAULT 1,  
             PRIMARY KEY id(id)
             )$charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


register_activation_hook(__FILE__, 'table_creator');


add_action('admin_menu', 'da_display_esm_menu');
function da_display_esm_menu()
{

    add_menu_page('EMS', 'EMS', 'manage_options', 'emp-list', 'da_ems_list_callback');
    add_submenu_page('emp-list', 'Employee List', 'Employee List', 'manage_options', 'emp-list', 'da_ems_list_callback');
    add_submenu_page('emp-list', 'Add Employee', 'Add Employee', 'manage_options', 'add-emp', 'da_ems_add_callback');
    add_submenu_page(null, 'Update Employee', 'Update Employee', 'manage_options', 'update-emp', 'da_emp_update_call');
    add_submenu_page(null, 'Delete Employee', 'Delete Employee', 'manage_options', 'delete-emp', 'da_emp_delete_call');
    add_submenu_page('emp-list', 'Employee List Shortcode', 'Employee List Shortcode', 'edit_others_posts', 'emp-shotcode', 'da_emp_shortcode_call');
}

function da_ems_add_callback()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'suhail';
    $msg = '';
    $base64_image = '';

    if (isset($_POST['submit'])) {


        $emp_id = sanitize_text_field($_POST['emp_id']);
        $emp_name = sanitize_text_field($_POST['emp_name']);
        $emp_email = sanitize_email($_POST['emp_email']);
        $emp_dept = sanitize_text_field($_POST['emp_dept']);


        if (!empty($_FILES['emp_image']['tmp_name'])) {
            $image_data = file_get_contents($_FILES['emp_image']['tmp_name']);
            $base64_image = base64_encode($image_data);
        }

        // Prepare data for insertion
        $data = array(
            "emp_id" => $emp_id,
            'emp_name' => $emp_name,
            'emp_email' => $emp_email,
            'emp_dept' => $emp_dept,
            'emp_image' => $base64_image,
        );


        $result = $wpdb->insert($table_name, $data);

        if ($result) {
            $msg = "Saved Successfully";
        } else {
            $msg = "Failed to save data. Error: " . $wpdb->last_error;
        }
    }

?>
    <h4 id="msg"><?php echo $msg; ?></h4>

    <form method="post" enctype="multipart/form-data">

        <p>
            <label>EMP ID</label>
            <input type="text" name="emp_id" placeholder="Enter ID" required>
        </p>

        <p>
            <label>Name</label>
            <input type="text" name="emp_name" placeholder="Enter Name" required>
        </p>
        <p>
            <label>Email</label>
            <input type="email" name="emp_email" placeholder="Enter Email" required>
        </p>
        <p>
            <label>Department</label>
            <input type="text" name="emp_dept" placeholder="Enter Department" required>
        </p>

        <p>
            <label>Image</label>
            <input type="file" name="emp_image" accept=".jpg, .jpeg, .png">
        </p>

        <p>
            <button type="submit" name="submit">Submit</button>
        </p>
    </form>
<?php
}





function da_emp_shortcode_call()
{ ?>

    <p>
        <label>Shortcode</label>
        <input type="text" value="[employee_list]">
    </p>
    <?php }




add_shortcode('employee_list', 'da_ems_list_callback');
function da_ems_list_callback()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'suhail';
    $employee_list = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE status = 1", ""), ARRAY_A);

    if (count($employee_list) > 0) : ?>
        <div style="margin-top: 40px;">
            <table border="1" cellpadding="10">
                <tr>
                    <th>S.No.</th>
                    <th>EMP ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Image</th>
                    <?php if (is_admin()) : ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
                <?php $i = 1;
                foreach ($employee_list as $index => $employee) : ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $employee['emp_id']; ?></td>
                        <td><?php echo $employee['emp_name']; ?></td>
                        <td><?php echo $employee['emp_email']; ?></td>
                        <td><?php echo $employee['emp_dept']; ?></td>
                        <td>
                            <?php
                            if (!empty($employee['emp_image'])) {
                                $image_url = 'data:image;base64,' . $employee['emp_image'];
                                echo '<img src="' . esc_url($image_url) . '" alt="Employee Image" style="max-width: 100px; max-height: 100px;">';
                            } else {
                                echo 'No Image';
                            }
                            ?>
                        </td>
                        <?php if (is_admin()) : ?>
                            <td>
                                <a href="admin.php?page=update-emp&id=<?php echo $employee['id']; ?>">Edit</a>
                                <a href="admin.php?page=delete-emp&id=<?php echo $employee['id']; ?>">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php else :
        echo "<h2>Employee Record Not Found</h2>";
    endif;
}


function da_emp_update_call()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'suhail';
    $msg = '';
    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
    if (isset($_REQUEST['update'])) {
        if (!empty($id)) {
            $wpdb->update("$table_name", ["emp_id" => $_REQUEST['emp_id'], 'emp_name' => $_REQUEST['emp_name'], 'emp_email' => $_REQUEST['emp_email'], 'emp_dept' => $_REQUEST['emp_dept']], ["id" => $id]);
            $msg = 'Data updated';
        }
    }
    $employee_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name where id = %d", $id), ARRAY_A); ?>
    <h4><?php echo $msg; ?></h4>
    <form method="post">
        <p>
            <label>EMP ID</label>
            <input type="text" name="emp_id" placeholder="Enter ID" value="<?php echo $employee_details['emp_id']; ?>" required>
        </p>

        <p>
            <label>Name</label>
            <input type="text" name="emp_name" placeholder="Enter Name" value="<?php echo $employee_details['emp_name']; ?>" required>
        </p>
        <p>
            <label>Email</label>
            <input type="email" name="emp_email" placeholder="Enter Email" value="<?php echo $employee_details['emp_email']; ?>" required>
        </p>
        <p>
            <label>Department</label>
            <input type="text" name="emp_dept" placeholder="Enter Department" value="<?php echo $employee_details['emp_dept']; ?>" required>
        </p>
        <p>
            <button type="submit" name="update">Update</button>
        </p>
    </form>
    <?php }


function da_emp_delete_call()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'suhail';
    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";

    if (isset($_REQUEST['delete'])) {
        if ($_REQUEST['conf'] == 'yes') {
            $wpdb->update("$table_name", ["status" => 0], ["id" => $id]);
        }

    ?>
        <script>
            location.href = "<?php echo site_url(); ?>/wp-admin/admin.php?page=emp-list";
        </script>
    <?php
    }
    ?>
    <form method="post">
        <p>
            <label>Are you sure want to delete?</label><br>
            <input type="radio" name="conf" value="yes">Yes
            <input type="radio" name="conf" value="no" checked>No
        </p>
        <p>
            <button type="submit" name="delete">Delete</button>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
        </p>
    </form>
<?php
}

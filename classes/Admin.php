<?php


class Admin
{

    /** create admin page function */
    function my_menu_pages()
    {

        add_menu_page('Review Resume', 'Review Resume', 'manage_options', 'review-resume', array($this, 'admin_page'));

        add_menu_page('PayPal Detail', 'PayPal Detail', 'manage_options', 'paypal-review', array($this, 'paypal_transaction_page'));

        wp_enqueue_style('style', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');


        wp_enqueue_style('style', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css');
        wp_enqueue_script('script', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js');

        wp_enqueue_script('my-script', substr(plugin_dir_url(__FILE__), 0, strrpos(plugin_dir_url(__FILE__), "/", -2)) . "/" . 'assets/script.js',  array( 'jquery' ), '1', false);

//        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fc-woo-options-public.js', array( 'jquery' ), $this->version, false );


        wp_enqueue_script('script', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js');
        wp_enqueue_script('script', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js');
    }

    /** admin page with table and review form */
    function admin_page()
    {
        global $wpdb;

        /** getting data of resume */
        $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}resume_review INNER JOIN                    {$wpdb->prefix}users ON {$wpdb->prefix}users.ID = {$wpdb->prefix}resume_review.user_id INNER JOIN            {$wpdb->prefix}subscribe_deals on {$wpdb->prefix}subscribe_deals.id =                                        {$wpdb->prefix}resume_review.deal_id");
//        var_dump($result); exit;

        ?>

        <div class="container">
        <h2>Resume Review Data</h2><br>
        <table class="table table-striped table-bordered" id="myTable">
        <thead>
        <tr>
            <th>User Name</th>
            <th>Deal Name</th>
            <th>Resume</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($result as $key => $value) {
            ?>
            <tr>
                <td><?= $value->user_login ?></td>
                <td><?= $value->deal_name ?></td>
                <td><a href="<?php echo $value->file_url ?> " target="_blank"><?= $value->resume ?></a></td>
                <td>
                    <!--making modal for resume review-->
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                            data-target="#reviewModal<?php echo $value->resume_id; ?>">
                        Give Review
                    </button>
                </td>
            </tr>
            <!--Modal Form Starts-->
            <div class="modal fade" id="reviewModal<?php echo $value->resume_id; ?>" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <div class="form-group">
                                    <label for="name">User Name:</label>
                                    <input type="text" class="form-control" name="name"
                                           value="<?php echo $value->user_login; ?>" disabled>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="email">User Email:</label>
                                    <input type="text" class="form-control" name="email"
                                           value="<?php echo $value->user_email; ?>" disabled>
                                </div>
                                <input type="hidden" name="email" value="<?php echo $value->user_email ?>">
                                <input type="hidden" name="resume_id" value="<?php echo $value->resume_id ?>">
                                <input type="hidden" name="user_id" value="<?php echo $value->user_id ?>">
                                <br>
                                <div class="form-group">
                                    <label>Give Review:</label>
                                    <textarea class="form-control" rows="4" name="review"></textarea>
                                </div>
                                <br>
                                <input type="submit" class="btn btn-primary" name="review_form"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!--Modal Form Ends-->
            <?php
        }
        ?>
        </tbody>
        </table>
        </div>
        <?php
    }

    /** function to show the payPal transaction details */
    function paypal_transaction_page()
    {

        global $wpdb;

        /** getting data of resume */
        $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}subscribe_deals INNER JOIN                    {$wpdb->prefix}users ON {$wpdb->prefix}users.ID = {$wpdb->prefix}subscribe_deals.user_id INNER JOIN            {$wpdb->prefix}paypal_detail on {$wpdb->prefix}users.ID =                                        {$wpdb->prefix}paypal_detail.user_id");

//        var_dump($result); exit;

        ?>

        <div class="container">
            <h2>Resume Review Data</h2><br>
            <table class="table table-striped table-bordered table-responsive" id="myTable">
                <thead>
                <tr>
                    <th>User Name</th>
                    <th>Deal Name</th>
                    <th>Token</th>
                    <th>Transaction ID</th>
                    <th>Currency</th>
                    <th>CM</th>
                    <th>Item Number</th>
                    <th>Signature</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($result as $key => $value) {
                    ?>
                    <tr>
                        <td><?= $value->user_login ?></td>
                        <td><?= $value->deal_name ?></td>
                        <td><?= $value->token ?></td>
                        <td><?= $value->tx ?></td>
                        <td><?= $value->currency ?></td>
                        <td><?= $value->cm ?></td>
                        <td><?= $value->item_number ?></td>
                        <td><?= $value->signature ?></td>
                        <td><?= $value->status ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    }


    /** Saving the admin review into the table */
    function admin_page_code()
    {
        global $wpdb;

        if (isset($_POST['review_form'])) {
            $review = $_POST['review'];
            $resume_id = $_POST['resume_id'];
            $user_id = $_POST['user_id'];
            $to = $_POST['email'];

            $result = $wpdb->update("{$wpdb->prefix}resume_review", array('admin_review' => $review), array('resume_id' => $resume_id, 'user_id' => $user_id));

            $subject = 'Your Resume review';
            $message = 'Here is the review of your resume ' . $review;
            wp_mail($to, $subject, $message);
        }
    }
}
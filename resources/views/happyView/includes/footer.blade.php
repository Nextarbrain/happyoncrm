	<!-- Bootstrap JS -->
	<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{asset('assets/js/jquery.min.js')}}"></script>

	<script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<script src="{{asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
	<script src="{{asset('assets/plugins/chartjs/js/Chart.min.js')}}"></script>
	<script src="{{asset('assets/plugins/chartjs/js/Chart.extension.js')}}"></script>
	<script src="{{asset('assets/js/index.js')}}"></script>
	<!--app JS-->
	<script src="{{asset('assets/js/circle-progress.min.js')}}"></script>
	<script src="{{asset('assets/js/app.js')}}"></script>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>

    <script>
        const product_div = document.getElementById('product_div');
        if (product_div) {
            product_div.addEventListener("click", function() {
                var selectedItems = [];
                $('.product_checkbox:checked').each(function(){
                   var productId =  $(this).data('item-id');
                   selectedItems.push(productId);
                });
                var baseUrl = "{{URL::to('/')}}";
                if(selectedItems.length > 0){
                    $.ajax({
                        type: 'Delete',
                        url: baseUrl+'/delete-products',
                        headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            selectedItems: selectedItems
                        },
                        success: function(response){
                            toastr.success('Items deleted successfully');
                            $('#message_container').text('Items deleted successfully');
                            location.reload();
                        },
                        error: function(xhr,status, error){

                        }

                    });
                }
            });
        }

        const category_div = document.getElementById('category_div');
        if (category_div) {
            category_div.addEventListener("click", function() {
                var selectedItems = [];
                $('.category_checkbox:checked').each(function(){
                   selectedItems.push($(this).data('item-id'));
                });
                var baseUrl = "{{URL::to('/')}}";
                if(selectedItems.length > 0){
                    $.ajax({
                        type: 'Delete',
                        url: baseUrl+'/delete-category',
                        headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            selectedItems: selectedItems
                        },
                        success: function(response){
                            toastr.success('Category deleted successfully');
                            location.reload();
                        },
                        error: function(xhr,status, error){
                            toastr.error('Failed to delete category');
                        }
                    });
                }
            });
        }

        const membership_div = document.getElementById('membership_div');
        if(membership_div) {
            document.getElementById('membership_div').addEventListener("click", function() {
                var selectedItems = [];
                $('.membership_checkbox:checked').each(function(){
                var membershipId =  $(this).data('item-id');
                selectedItems.push(membershipId);
                });
                var baseUrl = "{{URL::to('/')}}";
                if(selectedItems.length > 0){

                    $.ajax({
                        type: 'Delete',
                        url: baseUrl+'/delete-membership',
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            selectedItems: selectedItems
                        },
                        success: function(response){
                            toastr.success('Items deleted successfully');
                            $('#message_container').text('Items deleted successfully');
                            location.reload();
                        },
                        error: function(xhr,status, error){

                        }

                    });
                }

            });
        }



        document.getElementById('coupon_div').addEventListener("click", function() {
            var selectedItems = [];
            $('.coupon_checkbox:checked').each(function(){
               var couponId =  $(this).data('item-id');
               selectedItems.push(couponId);
            });
            var baseUrl = "{{URL::to('/')}}";
            if(selectedItems.length > 0){
                $.ajax({
                    type: 'Delete',
                    url: baseUrl + '/delete-coupon',
                    headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        selectedItems: selectedItems
                    },
                    success: function(response){
                        toastr.success('Items deleted successfully');
                        $('#message_container').text('Items deleted successfully');
                        location.reload();
                    },
                    error: function(xhr,status, error){

                    }

                });
            }

        });

        document.getElementById('user_div').addEventListener("click", function() {
            var selectedItems = [];
            $('.user_checkbox:checked').each(function(){
               var userId =  $(this).data('item-id');
               selectedItems.push(userId);
            });
            var baseUrl = "{{URL::to('/')}}";
            if(selectedItems.length > 0){
                $.ajax({
                    type: 'Delete',
                    url: baseUrl + '/delete-user',
                    headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        selectedItems: selectedItems
                    },
                    success: function(response){
                        toastr.success('Items deleted successfully');
                        $('#message_container').text('Items deleted successfully');
                    },
                    error: function(xhr,status, error){

                    }

                });
            }

        });

        const product_edit = document.getElementById('product_edit');
        if (product_edit) {
            product_edit.addEventListener("click", function() {
                var selectedItems = [];
                $('.product_checkbox:checked').each(function(){
                   var productId =  $(this).data('item-id');
                   selectedItems.push(productId);
                });
                console.log(selectedItems);
                var baseUrl = "{{URL::to('/')}}";
                if(selectedItems.length > 0){
                    $.ajax({
                        type: 'GET',
                        url: baseUrl+'/edit-products-modal',
                        headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            selectedItems: selectedItems
                        },
                        success: function(response){
                            $('#editproductModal').addClass('show');
                            $('#editproductModal').show();
                            $('#editProductName').val(response.product_name);
                            $('#editpriceModalForm').val(response.product_price);
                            $('#editquantityModalForm').val(response.product_qty);
                            $('#edittaxModalForm').val(response.product_tax);
                            $('#productId').val(response.id);
                            if (response.branch_id) {
                                $('#editProductBranchId').val(response.branch_id.toString());
                            }
                            $('#editCategoryModalForm').val(response.category_id || '');
                            $('#editSkuModalForm').val(response.sku || '');
                            $('#editSortOrderModalForm').val(response.sort_order || 0);
                            $('#editIsActiveModalForm').prop('checked', response.is_active == 1 || response.is_active === true);
                            if (response.image_url) {
                                $('#editProductImagePreview').html('<img src="' + response.image_url + '" style="max-height:80px;border-radius:6px;border:1px solid #ddd;padding:2px;" alt="preview">');
                            } else {
                                $('#editProductImagePreview').html('');
                            }
                        },
                        error: function(xhr,status, error){

                        }

                    });
                }

            });
        }

        const category_edit = document.getElementById('category_edit');
        if (category_edit) {
            category_edit.addEventListener("click", function() {
                var selectedItems = [];
                $('.category_checkbox:checked').each(function(){
                   selectedItems.push($(this).data('item-id'));
                });
                var baseUrl = "{{URL::to('/')}}";
                if(selectedItems.length > 0){
                    $.ajax({
                        type: 'GET',
                        url: baseUrl+'/edit-category-modal',
                        headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            selectedItems: selectedItems[0]
                        },
                        success: function(response){
                            $('#editCategoryModal').addClass('show').show();
                            $('#editCategoryId').val(response.id);
                            $('#editCategoryNameModal').val(response.name);
                            $('#editCategoryColourModal').val(response.colour || '#0A5F59');
                            $('#editCategoryColourTextModal').val(response.colour || '#0A5F59');
                            $('#editCategoryIconModal').val(response.icon || '');
                            $('#editCategorySortOrderModal').val(response.sort_order || 0);
                            if (response.branch_id) {
                                $('#editCategoryBranchId').val(response.branch_id.toString());
                            }
                        }
                    });
                } else {
                    toastr.warning('Please select a category to edit');
                }
            });
        }
        const membership_edit = document.getElementById('membership_edit');
        if(membership_edit) {
             document.getElementById('membership_edit').addEventListener("click", function() {
                var selectedItems = [];
                $('.membership_checkbox:checked').each(function(){
                var memberId =  $(this).data('item-id');
                selectedItems.push(memberId);
                });
                console.log(selectedItems);
                var baseUrl = "{{URL::to('/')}}";
                if(selectedItems.length > 0){
                    $.ajax({
                        type: 'GET',
                        url: baseUrl+'/edit-member-modal',
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            selectedItems: selectedItems
                        },
                        success: function(response){
                            $('#editMemberModal').addClass('show');
                            $('#editMemberModal').show();
                            $('#editPlanName').val(response.plan_name);
                            $('#editPlanPrice').val(response.price);
                            $('#editPlanEntry').val(response.credits);
                            $('#memberId').val(response.id);
                        },
                        error: function(xhr,status, error){

                        }

                    });
                }

            });
        }

        document.getElementById('coupon_edit').addEventListener("click", function() {
            var selectedItems = [];
            $('.coupon_checkbox:checked').each(function(){
               var couponId =  $(this).data('item-id');
               selectedItems.push(couponId);
            });
            console.log(selectedItems);
            var baseUrl = "{{URL::to('/')}}";
            if(selectedItems.length > 0){
                $.ajax({
                    type: 'GET',
                    url: baseUrl+'/edit-coupon-modal',
                    headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        selectedItems: selectedItems
                    },
                    success: function(response){
                        $('#editCouponModal').addClass('show');
                        $('#editCouponModal').show();
                        $('#couponName').val(response.coupon_name);
                        $('#couponOffer').val(response.discount_offer);
                        $('#couponDiscount').val(response.discount);
                        $('#couponStart').val(response.start_from);
                        $('#couponValid').val(response.valid_till);
                        $('#couponType').val(response.discount_type);
                        $('#couponId').val(response.id);
                        $('#editCoupanBranchId').val(response.branch_id);
                    },
                    error: function(xhr,status, error){

                    }

                });
            }

        });
        const day_price_edit =  document.getElementById('day_price_edit');
        if(day_price_edit) {
                document.getElementById('day_price_edit').addEventListener("click", function() {
                var selectedItems = [];
                $('.day_price_checkbox:checked').each(function(){
                var dayPriceId =  $(this).data('item-id');
                selectedItems.push(dayPriceId);
                });
                console.log(selectedItems);
                var baseUrl = "{{URL::to('/')}}";
                if(selectedItems.length > 0){
                    $.ajax({
                        type: 'GET',
                        url: baseUrl+'/edit-dayprice-modal',
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            selectedItems: selectedItems
                        },
                        success: function(response){
                            $('#editDayPriceModal').addClass('show');
                            $('#editDayPriceModal').show();
                            $('#monday').val(response.monday);
                            $('#tuesday').val(response.tuesday);
                            $('#wednesday').val(response.wednesday);
                            $('#thursday').val(response.thursday);
                            $('#dayPriceId').val(response.id);
                        },
                        error: function(xhr,status, error){

                        }

                    });
                }

            });
        }


        document.getElementById('weekend_edit').addEventListener("click", function() {
            var selectedItems = [];
            $('.weekend_checkbox:checked').each(function(){
               var weekendId =  $(this).data('item-id');
               selectedItems.push(weekendId);
            });
            console.log(selectedItems);
            var baseUrl = "{{URL::to('/')}}";
            if(selectedItems.length > 0){
                $.ajax({
                    type: 'GET',
                    url: baseUrl+'/edit-weekend-modal',
                    headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        selectedItems: selectedItems
                    },
                    success: function(response){
                        $('#editWeekendModal').addClass('show');
                        $('#editWeekendModal').show();
                        $('#friday').val(response.friday);
                        $('#saturday').val(response.saturday);
                        $('#sunday').val(response.sunday);
                        $('#weekendId').val(response.id);
                    },
                    error: function(xhr,status, error){

                    }

                });
            }

        });



        function modalClose(){

            $('#editproductModal').hide();
            $('#editMemberModal').hide();
            $('#editCouponModal').hide();
             $('#editDayPriceModal').hide();
             $('#editWeekendModal').hide();
        }


        $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif(Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });
    </script>
</body>

</html>

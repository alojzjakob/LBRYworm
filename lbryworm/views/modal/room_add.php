<form id="add_room_form">
    <div>
        <h4>Add room</h4>
        <p>
            <input type="text" id="room_name" name="room_name" placeholder="Enter the room name">
        <p>
        <p class="error_message" id="error_message"></p>
        <p>
            <button type="submit" id="add_room">Add</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

add_room_handler();

</script>

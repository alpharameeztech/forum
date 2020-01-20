<template>


     <div class="frontUserNotifyBell">

        <div class="dropdown" >
            <a class="dropdown-toggle"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons outline-notifications icon-image-preview">
                    notifications_active
                </i> <span v-text="notifications.length"></span>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                <ul v-if="notifications.length > 0">

                    <li v-for="notification in notifications">
                            <a @click="markAsRead(notification)" class="dropdown-item" :href="notification.data.link" v-text="notification.data.message">asdas</a>
                    </li>

                </ul>

                <p v-else class="text-center"> Nothing to show yet</p>

            </div>
        </div>

     </div>


</template>


<script>
export default {

    data() {
        return {

            notifications: false

        }
    },

    created() {

        axios.get("/forum/profiles/" + window.App.user.name + "/notifications")

            .then(response => this.notifications = response.data);

    },
    methods: {


        markAsRead(notification){

            axios.delete("/forum/profiles/" + window.App.user.name + "/notifications/" + notification.id)
        }
    },
}
</script>

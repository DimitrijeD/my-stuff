export default () => {   
    return {
        users: {}, 
        filterForAddUsers:    [], // contains array of users ID-s that match string passed in /ChatWindow/Config/AddUsers input
        // filterForCreateGroup: [], // contains array of users ID-s that match string passed in /CreateChatGroup input
        defaultUser: {
            thumbnail: '/basic-images/basic-avatar.jpg',
            image: '/basic-images/basic-avatar.jpg',
            defaultUser: true,
            name: '[user deleted]'
        }
    }
}
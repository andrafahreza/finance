function getResponse(title, message, status)
{
    foto = ""

    if (status == "success") {
        foto = "../assets/images/notification/ok-48.png"
    } else {
        foto = "../assets/images/notification/high_priority-48.png"
    }

    notifier.show(title, message, status, foto, 3000);
}

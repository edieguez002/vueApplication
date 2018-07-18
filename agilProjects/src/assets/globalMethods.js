function toFormData(e) {
    var form_data = new FormData();
    for (var key in e){
        form_data.append(key, e[key]);
    }
    return form_data;
}

function dynamic_input(count, element_id) {
    const element = document.getElementById(element_id);
    element.onchange(function () {
        element.style = ""
    });
    const markup = "<tr><td><label for='memName'>Enter name: </label></td><td><input type='text' name='memName' id='memName'></td><td></td><td></td></tr>";

}

function chartTypeSelectFunction() {
    const x = document.getElementById("chart_type").value;
    const y = document.getElementById("sum_up");
    const z = document.getElementById("sum_up_txt");
    if (x === "1") {
        document.getElementById('chart_data').value = '{"17":10,"19":20}';
        document.getElementById('output_name').value = 'states.png';
        if (y.style.display === "block") {
            y.style.display = "none";
            z.style.display = "none";
        }

    }
    if (x === "2") {
        document.getElementById('chart_data').value = '{"33":5,"427":5, "415":5, "369":7}';
        document.getElementById('output_name').value = 'cities.png';

        if (y.style.display === "none") {
            y.style.display = "block";
            z.style.display = "block";
        }

    }
    if (x === "3") {
        document.getElementById('chart_data').value = '{"34":10,"108":20, "56":15}';
        document.getElementById('output_name').value = 'world.png';
        if (y.style.display === "block") {
            y.style.display = "none";
            z.style.display = "none";
        }
    }
}
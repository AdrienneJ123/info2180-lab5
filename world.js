// When the page finishes loading, it runs all of this code
window.onload = () => {
    // Get references to the buttons, result div, and the text field
    const searchBtn =document.querySelector("#lookup");
    const cityBtn=document.querySelector("#city-lookup");
    const result =document.querySelector("#result");
    const input=document.querySelector("#country");

    //This function sends an AJAX request to world.php depending on the type of lookup we want (country or cities). It then displays whatever HTML the PHP file returns.
    function fetchData(type) {

        // Get whatever the user typed and remove any extra spaces
        const country = input.value.trim();
        //this makes sure the value is safe for a URL
        const url= `world.php?country=${encodeURIComponent(country)}&lookup=${type}`;

        // This uses the Fetch API to request data from world.php
        fetch(url)
            .then(response => response.text()) // Convert response to regular text
            .then(data => {
                // This displays the HTML returned by PHP inside the result 
                result.innerHTML=data;
            })
            .catch(error => {
                // If something goes wrong it show an error message
                result.innerHTML= "<p>Error fetching data...</p>";});
    }
    // When the user clicks the "Lookup Country" button,
    // call fetchData() and tell it we want the country information
    searchBtn.addEventListener("click", () => fetchData("country"));
    // When they click "Lookup Cities" it fetchs the city information instead
    cityBtn.addEventListener("click", () => fetchData("cities"));
};

$(document).ready(function () {

    fetchAndDisplayProperties();
});

function fetchAndDisplayProperties() {
    $.ajax({
        url: 'fetchProperties.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                console.error('Error fetching properties:', data.error);
            } else {
                updatePropertyList(data);
            }
        },
        error: function (xhr, status, error) {
            console.error('Failed to fetch properties:', error);
        }
    });
}

function updatePropertyList(properties) {
    const propertyList = $('#propertyList');
    propertyList.empty();

    if (properties.length > 0) {
        properties.forEach(function (property) {
            const propertyHtml = `
    <div class="property" data-propertyid="${property.PropertyID}">
        <img src="${property.ImagePaths}" alt="${property.PropertyName}">
        <h3>${property.PropertyName}</h3>
        <p>Property Type: ${property.PropertyType}</p>
        <p>Location: ${property.Location}</p>
        <p>Size: ${property.Size}sqf</p>
        <p>Price: $${property.Price}</p>
        <p>Year To Build: ${property.YearToBuild}</p>
        <p>Contact Realtor: ${property.ContactRealtor}</p>
          <p>Status: ${property.Status}</p>
        <button onclick="location.href='sendemail.html'">Send Email</button>
        <button onclick="manageProperty(${property.PropertyID})">Manage Property</button>
    </div>`;

            propertyList.append(propertyHtml);
        });
    } else {
        propertyList.append('<p>No properties found</p>');
    }
}

function manageProperty(propertyID) {
    console.log("Managing property with ID:", propertyID);
    window.location.href = `manageproperties.html?propertyID=${propertyID}`;
}

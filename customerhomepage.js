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
    </div>`;

            propertyList.append(propertyHtml);
        });
    } else {
        propertyList.append('<p>No properties found</p>');
    }
}

$(document).ready(function () {
    populateDropdown('propertyType', 'getPropertyTypes.php');
    populateDropdown('priceRange', 'getPriceRanges.php');
    populateDropdown('location', 'getLocations.php');
    populateDropdown('yearToBuild', 'getYearsToBuild.php');
});

function populateDropdown(dropdownId, sourceUrl) {
    $.ajax({
        url: sourceUrl,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (!data.error) {
                const dropdown = $('#' + dropdownId);
                dropdown.empty();
                data.forEach(option => dropdown.append(`<option value="${option}">${option}</option>`));
            }
        },
        error: function (xhr, status, error) {
            console.error(`Failed to fetch ${dropdownId} options:`, error);
        }
    });
}

function searchProperties() {
    console.log('Search button clicked');

    const propertyType = $('#propertyType').val();
    const priceRange = $('#priceRange').val();
    const location = $('#location').val();
    const yearToBuild = $('#yearToBuild').val();

    $.ajax({
        url: 'search.php',
        method: 'GET',
        data: {
            propertyType: propertyType,
            priceRange: priceRange,
            location: location,
            yearToBuild: yearToBuild
        },
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                console.error('Error searching properties:', data.error);
            } else {
                console.log('Search results:', data);
                updatePropertyList(data);
            }
        },
        error: function (xhr, status, error) {
            console.error('Failed to search properties:', error);
        }
    });
}

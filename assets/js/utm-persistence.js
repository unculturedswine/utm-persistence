(function() {
    if (typeof utmPersistenceSettings === 'undefined') {
        return;
    }
    
    var domainsToDecorate = utmPersistenceSettings.domains || [];
    var queryParams = utmPersistenceSettings.params || [];
    
    // Only proceed if we have domains and params configured
    if (domainsToDecorate.length === 0 || queryParams.length === 0) {
        return;
    }
    
    // Decorate all links on the page
    var links = document.querySelectorAll('a');
    
    // Check if links contain domain from the domainsToDecorate array and then decorates
    for (var linkIndex = 0; linkIndex < links.length; linkIndex++) {
        for (var domainIndex = 0; domainIndex < domainsToDecorate.length; domainIndex++) {
            if (links[linkIndex].href.indexOf(domainsToDecorate[domainIndex]) > -1 && links[linkIndex].href.indexOf("#") === -1) {
                links[linkIndex].href = decorateUrl(links[linkIndex].href);
            }
        }
    }
    
    // Decorates the URL with query params
    function decorateUrl(urlToDecorate) {
        var collectedQueryParams = [];
        for (var queryIndex = 0; queryIndex < queryParams.length; queryIndex++) {
            if (getQueryParam(queryParams[queryIndex])) {
                collectedQueryParams.push(queryParams[queryIndex] + '=' + getQueryParam(queryParams[queryIndex]));
            }
        }
        
        // Only modify URL if we have params to add
        if (collectedQueryParams.length < 1) {
            return urlToDecorate;
        }
        
        // Check if URL already has query string
        var separator = (urlToDecorate.indexOf('?') === -1) ? '?' : '&';
        return urlToDecorate + separator + collectedQueryParams.join('&');
    }
    
    // Function that retrieves the value of a query parameter
    function getQueryParam(name) {
        var regex = new RegExp('[?&]' + encodeURIComponent(name) + '=([^&]*)');
        var results = regex.exec(window.location.search);
        if (results) {
            return decodeURIComponent(results[1]);
        }
        return null;
    }
    
    // Also handle dynamically added links
    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            var newLinks = node.tagName === 'A' ? [node] : node.querySelectorAll('a');
                            if (newLinks.length) {
                                for (var i = 0; i < newLinks.length; i++) {
                                    for (var j = 0; j < domainsToDecorate.length; j++) {
                                        if (newLinks[i].href.indexOf(domainsToDecorate[j]) > -1 && newLinks[i].href.indexOf("#") === -1) {
                                            newLinks[i].href = decorateUrl(newLinks[i].href);
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
})();
function parseUrl(url) {
    const parts = url.split('/');
    const protocol = parts[0].replace(':', '');
    const hostname = parts[2].split(':')[0];
    const port = parts[2].split(':')[1] || (protocol === 'https' ? '443' : '80');
    console.log(protocol, hostname, port)
    return [protocol.replace(/"/g, ''), hostname, parseInt(port.replace(/"/g, ''))];
}

export default parseUrl;
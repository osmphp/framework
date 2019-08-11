# Creating SSL Certificate Using Local Authority #

1. With `root` user, create SSL certificate configuration file `/etc/nginx/ssl/{project_domain}.txt`:

	    authorityKeyIdentifier=keyid,issuer
	    basicConstraints=CA:FALSE
	    keyUsage = digitalSignature, nonRepudiation, keyEncipherment, dataEncipherment
	    subjectAltName = @alt_names

	    [alt_names]
	    DNS.1 = {project_domain}

2. With `root` user, run in shell:

	    cd /etc/nginx/ssl
	    openssl genrsa -out {project_domain}.key 2048
	    openssl req -new -key {project_domain}.key -out {project_domain}.csr
	    openssl x509 -req -in {project_domain}.csr -CA ca.crt -CAkey ca.key -CAcreateserial -out {project_domain}.crt -days 1825 -sha256 -extfile {project_domain}.txt
	    chmod 600 *

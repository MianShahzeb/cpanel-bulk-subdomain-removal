# Name: cPanel Bulk Subdomains Delete 1.1
# Requirements to configure
1. cPanel Username
2. cPanel Password
3. cPanel Skin
4. Root Domain Name
5. Upload file.txt to public_html or any directory
			Domains sample
			sub-domain
			subdomain2
			subdomain5
	Each line must contain on 1 sub domain in file.txt
6. Structure of $request
 	6.1	Don't forget to sign in into your cpanel and check if you have the same prefix (cpsess16xxxxxx55)
	6.2	=== https://[host here]:[port here]/cpsess/frontend/cpanel_skin/subdomain/dodeldomain.html?domain=[sub-domain here].[domain here]
	6.3	Sample:
		"/$cpess/frontend/$cpanel_skin/subdomain/dodeldomain.html?domain=$subd.$domain"
7. Port should be 2083 or 2082 (Normally it will 2083).

Make Changes in Initial Settings and on line number 26, 147

# Credits
cPanel Hosting https://hosting.biz - IBX Technologies https://ibxtechnologies.com - cPanel https://cpanel.com

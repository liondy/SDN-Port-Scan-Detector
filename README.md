# SDN Port Scan Detector

Web Interface to monitor if there is a port scanning activites in a SDN environment.

## Instalation

To run this web interface on your environment, please do the following:

1. clone this repository `git clone https://github.com/liondy/SDN-Port-Scan-Detector.git`.
2. place it in your web server directory
3. set up your database. I'm using phpmyadmin. My database is `sdn_port_scanning` and has 3 tables. To see all the tables config, please see my [gitlab](https://gitlab.com/liondy/skripsi)
4. get the port scan detector program from [here](https://gitlab.com/liondy/skripsi/-/blob/master/monitor-v2.py)
5. run the python program, it will automatically export the `nmap` packet to database
6. head to the website, it should display the log and automatically refresh every second

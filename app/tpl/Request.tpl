<?xml version="1.0" encoding="UTF-8" ?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xmm="http://namespace.softwareag.com/entirex/xml/mapping" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/">
    <SOAP-ENV:Header>
    </SOAP-ENV:Header>
    <SOAP-ENV:Body>
        <m:WSDLVEGA xmlns:m="urn:com-softwareag-entirex-rpc:CSS-BASE-WSDLVEGA">
            <USER-ID>{{ params.user_id }}</USER-ID>
            <FUNC>{{ params.func }}</FUNC>
            <FIRMA>106</FIRMA>
            <REQBUFB64>{{ params.cmd | ebase64 }}</REQBUFB64>
            <SIGNB64>{{ params.sign | ebase64 }}</SIGNB64>
        </m:WSDLVEGA>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>

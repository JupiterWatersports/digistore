start rmiregistry 5678
pause
start java -Djava.rmi.server.codebase=file:/brmi2/client  -Djava.security.policy=file:/brmi2/client/policy  RMIBarcode.SimulatorClientImpl 5678


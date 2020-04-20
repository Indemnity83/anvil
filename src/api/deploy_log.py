from flask_restful import Resource


class DeployLog(Resource):
    log_file_path = '/srv/anvil/appdata/deploy.log'

    def get(self):
        log_file = open(self.log_file_path, 'w')
        
        return log_file.read()

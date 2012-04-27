"""Session table that stores session data as JSON

Works similarily as :mod:`django.contrib.sessions.backends.db`

To use this add to your settings.py::

    SESSION_ENGINE = 'djangosessionjson'

"""

from django.utils import simplejson as json

from django.conf import settings
from django.core.exceptions import SuspiciousOperation
from django.utils.hashcompat import md5_constructor

from django.contrib.sessions.backends.db import SessionStore as DBSessionStore

class SessionStore(DBSessionStore):
    def encode(self, session_dict):
        "Returns the given session dictionary pickled and encoded as a string."
        pickled = json.dumps(session_dict)
        return pickled
        # FIXME: No md5
        #pickled_md5 = md5_constructor(pickled + settings.SECRET_KEY).hexdigest()
        #return pickled + pickled_md5

    def decode(self, session_data):
        pickled = session_data
        # FIXME: No md5
        #pickled, tamper_check = session_data[:-32], session_data[-32:]
        #if md5_constructor(pickled + settings.SECRET_KEY).hexdigest() != tamper_check:
        #    raise SuspiciousOperation("User tampered with session cookie.")
        try:
            
            return json.loads(pickled)
        # Unpickling can cause a variety of exceptions. If something happens,
        # just return an empty dictionary (an empty session).
        except:
            return {}

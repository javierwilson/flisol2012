from django.http import HttpResponse
import pprint

def home(request):
    request.session['contador'] += 1;
    data = pprint.pformat(request.session.items())
    return HttpResponse("Django dice...<pre>" + data)

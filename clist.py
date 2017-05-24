import urllib.request
import sys
import xmltodict, json
import pprint

pp = pprint.PrettyPrinter(indent=2)

cities = list()
with open('cities.txt') as f:
    cities = f.read().splitlines() 

search_terms = [
    #'php+slim+remote',
    #'django+remote',
    'python+mysql+remote',
    'oracle+remote'
    ]

for search_term in search_terms:
    print("Search=%s::" % search_term)
    for city in cities:
        getxml_url = ("http://%s.craigslist.org/search/jjj?format=rss&query=%s" % (city, search_term))
        getxml = urllib.request.urlopen(getxml_url).read()
        res = xmltodict.parse(getxml)
        if res['rdf:RDF']['channel']['items']['rdf:Seq'] is not None:
            if type(res['rdf:RDF']['channel']['items']['rdf:Seq']['rdf:li']) is list:
                for item in res['rdf:RDF']['channel']['items']['rdf:Seq']['rdf:li']:
                    print(item['@rdf:resource'])
            else:
                print(res['rdf:RDF']['channel']['items']['rdf:Seq']['rdf:li']['@rdf:resource'])
        #for item in res['rds:RDF']['channel']['items']:
        #    print("  "+item)
        #sys.exit()
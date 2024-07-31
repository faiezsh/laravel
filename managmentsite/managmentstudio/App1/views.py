# from django.shortcuts import render, redirect
# from App1 import models
# from App1.filters import Userfilter
# import requests
import json
import random
from Hill_climb.venv import Pieces, Scale, Door, Window, DistributionAlgorithm, Algorithm
from django.http import JsonResponse
import math
from Hill_climb.venv.DistributionAlgorithm import QQ

# Create your views here.
from django.views.decorators.csrf import csrf_exempt

qq = QQ()
'''
def insert_user(request):
    firstname = request.POST.get('tfirstname', False)
    lastname = request.POST.get('tlastname', False)
    us = models.User(first_name=firstname, last_name=lastname);
    us.save()
    return render(request, 'user.html', {})


def update_user(request, id):
    users = models.User.objects.get(id=id)
    firstname = request.POST.get('tfirstname', False)
    lastname = request.POST.get('tlastname', False)
    us = models.User(id=id, first_name=firstname, last_name=lastname);
    us.save()
    return render(request, 'update.html', {'users': users})


def search_user(request):
    user_list = models.User.objects.all()
    userfilter = Userfilter(request.GET, queryset=user_list)
    return render(request, 'search.html', {'filter': userfilter})


def delete_user(request, id):
    users = models.User.objects.get(id=id)
    users.delete()
    return redirect("/search")


def show_number(request):
    numbers = models.Phone.objects.all()
    return render(request, "number.html", {'numbers': numbers})


def show_car(request):
    cars = models.Cars.objects.all()
    return render(request, "car.html", {'cars': cars})


# Get API from :
'''
from App1.models import Rate


# def getApi(request):
# response = requests.get(' http://127.0.0.1:8000/json1').json()
# return render(request, "Api.html", {'response': response})
@csrf_exempt
def get_data_json(request):
    data = json.loads(request.body)
    print(data)
    for i in data['models']:
        x1 = round((i['x'] - 0) / data['length'], 1)
        x2 = round((data['length'] - i['x']) / data['length'], 1)
        y1 = round((i['y'] - 0) / data['highe'], 1)
        y2 = round((data['highe'] - i['y']) / data['highe'], 1)
        z1 = round((i['z'] - 0) / data['width'], 1)
        z2 = round((data['width'] - i['z']) / data['width'], 1)

        if not Rate.objects.filter(x1=x1, x2=x2, y1=y1, y2=y2, z1=z1, z2=z2).exists():
            obj = Rate(x1=x1, x2=x2, y1=y1, y2=y2, z1=z1, z2=z2, modelid=i['modelId'],
                       rotate=i['rotate'], rate=data['rate'], ratecount=1)
            obj.save()
        else:
            us = Rate.objects.get(x1=x1)
            x = us.ratecount * us.rate

            y = us.ratecount + 1

            us.rate = (x + data['rate']) / y
            us.ratecount = us.ratecount + 1
            us.save()


def search_in_dict(dict, value):
    s = 0
    id = 0
    for i in dict:
        if i != value:
            if dict[i] > s:
                s = dict[i]
                id = i
    return id


def check_arr(data, id, x, y, z):
    current = Scale.Scale()
    center = Scale.Scale()
    ch = False
    for i in data['models']:
        if i['id'] == id:
            ch = qq.checkCenter(center.setXYZ(x, y, z),
                                i['x'], i['y'], i['z']
                                , current.setXYZ(x, y, z))
            if ch:
                qq.visit.clear()
                for newI in qq.coordinates.keys():
                    qq.visit[((round(newI[0][0], 1), round(newI[0][1], 1)), round(newI[1], 1))] = False
                qq.bookings([x, y, z], i['x'], i['y'], i['z'], current.setXYZ(x, y, z))
                break
    return ch


@csrf_exempt
def MainJson(request):
    arr_obj = []
    arr_door = []
    arr_window = []
    obj1 = Pieces.Pieces()
    obj2 = Door.Door()
    obj3 = Window.Window()
    scale_obj = Scale.Scale()
    global jsonString
    my_list = []
    data = json.loads(request.body)

    qq.Generate_Coordinates(data['length'], data['width'], data['highe'])

    qq.room_scale.setXYZ(data['length'], data['width'], data['highe'])
    if not data['models']:
        print("Hello array is empty")
    else:
        for i in data['models']:
            obj1.setPropertise(scale_obj.setXYZ(i['x'], i['y'], i['z']), i['type'])
            arr_obj.append(obj1)

        for j in data['doors']:
            obj2.setDoor(j['width'], j['pos'], j['wall'])
            arr_door.append(obj2)

        for k in data['windows']:
            obj3.setWindow(k['width'], k['pos'], k['wall'])
            arr_window.append(obj3)
        # --------------------------------------------------------------------------------------------#

        for door in arr_door:
            if door.wall == 1:
                Z = qq.Offset * math.floor((qq.room_scale.z - door.width / 2) / qq.Offset)
                Y = qq.Offset * math.ceil(1.05 / qq.Offset)
                qq.visit.clear()
                for i in qq.coordinates.keys():
                    qq.visit[((round(i[0][0], 1), round(i[0][1], 1)), round(i[1], 1))] = False
                qq.bookings([door.pos, Y, Z], door.width / 2, 1.05, door.width / 2, Scale.Scale.setXYZ(door.pos, Y, Z))
            elif door.wall == 4:
                Z = qq.Offset * math.ceil((door.width / 2) / qq.Offset)
                Y = qq.Offset * math.ceil(1.05 / qq.Offset)
                qq.visit.clear()
                for i in qq.coordinates.keys():
                    qq.visit[((round(i[0][0], 1), round(i[0][1], 1)), round(i[1], 1))] = False
                qq.bookings([door.pos, Y, Z], door.width / 2, 1.05, door.width / 2, Scale.Scale.setXYZ(door.pos, Y, Z))
            elif door.wall == 3:
                X = qq.Offset * math.ceil((door.width / 2) / qq.Offset)
                Y = qq.Offset * math.ceil(1.05 / qq.Offset)
                qq.visit.clear()
                for i in qq.coordinates.keys():
                    qq.visit[((round(i[0][0], 1), round(i[0][1], 1)), round(i[1], 1))] = False
                qq.bookings([X, Y, door.pos], door.width / 2, 1.05, door.width / 2, Scale.Scale.setXYZ(X, Y, door.pos))
            else:
                print(qq.room_scale.x)
                X = qq.Offset * math.floor((qq.room_scale.x - door.width / 2) / qq.Offset)
                Y = qq.Offset * math.ceil(1.05 / qq.Offset)
                qq.visit.clear()
                for i in qq.coordinates.keys():
                    qq.visit[((round(i[0][0], 1), round(i[0][1], 1)), round(i[1], 1))] = False
                    current = Scale.Scale()
                    door.pos = qq.Offset * math.ceil(door.pos / qq.Offset)
                    current.setXYZ(X, Y, door.pos)
                qq.bookings([X, Y, door.pos], door.width / 2, 1.05, door.width / 2, current)

            # ---------------------------------------------------------------------------------------------#
        for window in arr_window:
            if window.wall == 1:
                Z = qq.Offset * math.floor((qq.room_scale.z - window.width / 4) / qq.Offset)
                Y = qq.Offset * math.ceil(0.55 / qq.Offset)
                qq.visit.clear()
                for i in qq.coordinates.keys():
                    qq.visit[((round(i[0][0], 1), round(i[0][1], 1)), round(i[1], 1))] = False
                s = Scale.Scale()
                s.setXYZ(window.pos, Y, Z)
                qq.bookings([window.pos, Y, Z], window.width / 2, 0.55, window.width / 4, s)
            elif window.wall == 4:
                Z = qq.Offset * math.ceil((window.width / 4) / qq.Offset)
                Y = qq.Offset * math.ceil(0.55 / qq.Offset)
                qq.visit.clear()
                current = Scale.Scale()
                current.setXYZ(window.pos, Y,Z )
                for i in qq.coordinates.keys():
                    qq.visit[((round(i[0][0], 1), round(i[0][1], 1)), round(i[1], 1))] = False
                qq.bookings([window.pos, Y, Z], window.width / 2, 0.55, window.width / 4,
                            current)
            elif window.wall == 3:
                X = qq.Offset * math.ceil((window.width / 4) / qq.Offset)
                Y = qq.Offset * math.ceil(0.55 / qq.Offset)
                qq.visit.clear()
                current = Scale.Scale()
                current.setXYZ(X, Y, window.pos)
                for i in qq.coordinates.keys():
                    qq.visit[((round(i[0][0], 1), round(i[0][1], 1)), round(i[1], 1))] = False
                qq.bookings([X, Y, window.pos], window.width / 4, 0.55, window.width / 2,
                            current)
            else:
                X = qq.Offset * math.floor((qq.room_scale.x - window.width / 4) / qq.Offset)
                Y = qq.Offset * math.ceil(0.55 / qq.Offset)
                qq.visit.clear()
                current=Scale.Scale()
                current.setXYZ(X, Y, window.pos)
                for i in qq.coordinates.keys():
                    qq.visit[((round(i[0][0], 1), round(i[0][1], 1)), round(i[1], 1))] = False
                qq.bookings([X, Y, window.pos], window.width / 4, 0.55, window.width / 2,
                            current)
        # ----------------------------------------------------------------------------------------------#
        arr_algo = []
        dic = {}
        model = []
        allP = {}

        m = 0
        for i in data['models']:
            # if i['modelid']=0
            size = Scale.Scale()
            size.setXYZ(i['x'], i['y'], i['z'])
            allP.setdefault(i['id'], Pieces.Pieces(size, i['type']))
            if Rate.objects.filter(modelid=i['id']).exists():
                print("--------------Model", i['id'], "is exists--------------")
                m3 = i['x'] * i['y'] * i['z']
                dic.setdefault(i['id'], m3)
                m += 1
                # R = Rate.objects.filter(modelid=i['modelid'], rate__gt=0).order_by('-rate')
                # print(R[0].modelid,R[0])
                # if R[0].modelid==i['modelid']:
                # arr_algo.append(R)
            else:
                model.append(i)

                # print(model[0]['modelid'])

        id = 0
        global gg

        for i in range(0, m):
            id = search_in_dict(dic, id)
            R = Rate.objects.filter(modelid=id, rate__gt=0).order_by('-rate')
            arr_algo.append(list(R))
        print(arr_algo)
        piece = Pieces.Pieces()
        for i in arr_algo:
            id = -1
            gg = True
            for j in i:
                modelid = j.modelid
                rate = j.rate
                rotate = j.rotate
                x1 = j.x1
                x = round(data['length'] * x1, 1)
                y1 = j.y1
                y = round(data['width'] * y1, 1)
                z1 = j.z1
                z = round(data['highe'] * z1, 1)
                print("----------------The model ", modelid, "------------------")
                print(x, y, z,"The Rate : ", rate)
                xu = qq.Offset * math.ceil(x / qq.Offset)
                yu = qq.Offset * math.ceil(y / qq.Offset)
                zu = qq.Offset * math.ceil(z / qq.Offset)
                id = modelid
                ch = check_arr(data, modelid, xu, yu, zu)
                print(ch)

                if ch:
                    gg = False
                    # return jason response
                    my_list.append({'modelid': modelid, 'x': round(x, 1), 'y': round(y, 1),
                                    'z': round(z, 1), 'rotate': rotate})
                    break
            if gg:
                print("--------------Model ", id, " all position is booked--------------")
                choos1 = qq.choosePos(allP[id])
                print("----------------The New Position of model ", id, "------------------")
                print(choos1)
                # return json response
                my_list.append({'modelid': id, 'x': round(choos1[0], 1), 'y': round(choos1[1], 1),
                                'z': round(choos1[2], 1), 'rotate': random.randint(0, 360)})

        s_obj = Scale.Scale()
        for ele in model:
            s_x = ele['x']
            s_y = ele['y']
            s_z = ele['z']
            s_obj.setXYZ(s_x, s_y, s_z)
            place_type = ele['type']
            P_obj = Pieces.Pieces(s_obj, place_type)
            print("--------------Model", ele['id'], "is not exists--------------")
            choos = qq.choosePos(P_obj)
            print("----------------The New Position of model ", ele['id'], "------------------")
            print(choos)
            my_list.append({'modelid': ele['id'], 'x': round(choos[0], 1), 'y': round(choos[1], 1),
                            'z': round(choos[2], 1), 'rotate': random.randint(0, 360)})

        # arr_up = list(filter(lambda item: item is not None, arr_algo))
        # print(arr_algo[1][0].modelid)
    dic_Room = {'length': data['length'], 'width': data['width'], 'highe': data['highe'],
                'models': my_list, 'doors': data['doors'], 'windows': data['windows']}
    # my_list=[]
    return JsonResponse(dic_Room, safe=False)


models = {}


@csrf_exempt
def get_all_model(request):
    global arr_m
    global all_m
    all_m = []
    arr_m = []
    R = Rate.objects.all()
    arr_m.append(R)
    print(arr_m)
    for i in arr_m:
        for j in i:
            modelid = j.modelid
            x1 = j.x1
            x2 = j.x2
            y1 = j.y1
            y2 = j.y2
            z1 = j.z1
            z2 = j.z2
            all_m.append({'modelid': modelid, 'x1': round(x1, 1), 'x2': round(x2, 1),
                          'y1': round(y1, 1), 'y2': round(y2, 1),
                          'z1': round(z1, 1), 'z2': round(z2, 1)})
    models = {'models': all_m}
    arr_m = []
    all_m = []
    return JsonResponse(models, safe=False)
    # obj = Rate(x1=x1, x2=x2, y1=y1, y2=y2, z1=z1, z2=z2, modelid=i['modelid'],
    # rotate=0, rate=data['rate'], ratecount=5)

    # Rates.objects.create(x1=x1, x2=x2, y1=y1, y2=y2, z1=z1, z2=z2, modelid=i['modelid'], rotate=0,
    #                    rate=data['rate'], ratecount=0)
    # #if not models.Rates.objects.filter(x1=x1).exists():

    # if not models.Rates.objects.filter(x2=round(x2,1)).exists():
    # us = models.Rates(x1=x1, x2=x2, y1=y1, y2=y2, z1=z1, z2=z2, modelid=i['modelid'], rotate=0,
    # rate=data['rate'], ratecount=0)
    # us.save()

    # for i in data['models']
    # users = models.User.objects.get(id=i['_modelid_'])
    # us = models.Rates(rate=data['rate']);
    # return JsonResponse({'data':data})
    # us.save()
    # return JsonResponse({'data': data})

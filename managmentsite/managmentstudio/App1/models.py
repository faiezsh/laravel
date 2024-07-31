from django.db import models
# from django.db import connections


# Create your models here.
class Rate(models.Model):
    objects = models.Manager()
    x1 = models.FloatField()
    x2 = models.FloatField()
    y1 = models.FloatField()
    y2 = models.FloatField()
    z1 = models.FloatField()
    z2 = models.FloatField()
    modelid = models.IntegerField()  # Field name made lowercase.
    rotate = models.FloatField()
    rate = models.FloatField()
    ratecount = models.IntegerField()  # Field name made lowercase.



    class Meta:
        db_table = "rates"

'''
class Student(models.Model):
    first_name = models.CharField(max_length=15)
    last_name = models.CharField(max_length=15)
    age = models.IntegerField(default=15)
    date_birth = models.DateTimeField()

    def __str__(self):
        return self.first_name


class User(models.Model):
    objects = models.Manager()
    first_name = models.CharField(max_length=15)
    last_name = models.CharField(max_length=15)

    class Meta:
        db_table = "user"


class Phone(models.Model):
    objects = models.Manager()
    phone_number = models.CharField(max_length=15)

    class Meta:
        db_table = "phone"


class Cars(models.Model):
    objects = models.Manager()
    car_id = models.AutoField(primary_key=True)
    car_name = models.CharField(max_length=50)

    class Meta:
        managed = False
        db_table = 'cars'
'''

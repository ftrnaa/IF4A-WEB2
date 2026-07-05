from locust import HttpUser, task, between

class WebsiteUser(HttpUser):
    wait_time = between(1, 2)

    @task(5)
    def home(self):
        self.client.get("/")

    @task(3)
    def koleksi(self):
        self.client.get("/koleksi")

    @task(2)
    def tentang(self):
        self.client.get("/tentang")